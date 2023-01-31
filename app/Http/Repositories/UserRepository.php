<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Http\Response\BodyResponse;
use App\Models\User;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository
{
    /**
     * Base repository Constructor
     *
     * @param Application $app
     * @param string $messageResponseKey
     * @throws Exception
     */
    public function __construct(Application $app)
    {
        parent::__construct($app, Lang::get('data.user'));
    }

    /**
     * Set model class
     * @return Model
     */
    public function model()
    {
        return $this->model = User::class;
    }

    /**
     * Get create validation
     * @return object Object of rules, messages, attributes. 
     */
    public function createValidation()
    {
        return ((object) [
            'rules' => [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }

    /**
     * Get update validation
     * @return object Object of rules, messages, attributes. 
     */
    public function updateValidation()
    {
        return ((object) [
            'rules' => [
                'name' => ['required', 'string', 'max:255']
            ],
            'messages' => [],
            'attributes' => []
        ]);
    }

    /**
     * Get all data
     *
     * @param string $order Order asc or desc
     * @param array $search Searching column and keyword key
     * @param array $columns Output columns
     * @param int $count Data per page
     * @return BodyResponse
     */
    public function indexWithProfile(string $order = 'desc', array $search = [], $columns = ['*'], $count = 0): BodyResponse
    {
        $body = new BodyResponse();
        $repo = $this;
        try {
            if ($count > 0) $this->perPage = $count;
            $data = $this->allQuery($search)->orderBy('created_at', $order)
                ->permission('managed by ' . $this->currentHighestRole()['name'])
                ->with('profile')->with('roles')->paginate($this->getPerPage());
            $data->transform(function ($item) use ($columns, $repo) {
                $repo->clearColumn($columns, $item);
                $repo->transformProfile($item);
                $repo->transformRoles($item);
                return $item;
            });
            $body->setBodyMessage($this->messageResponse['successGet']);
            $body->setBodyData($data);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /**
     * Get all trashed data
     *
     * @param string $order Order asc or desc
     * @param array $search Searching column and keyword key
     * @param array $columns Output columns
     * @param int $count Data per page
     * @return BodyResponse
     */
    public function indexWithProfileTrashed(string $order = 'desc', array $search = [], $columns = ['*'], $count = 0): BodyResponse
    {
        $body = new BodyResponse();
        $repo = $this;
        try {
            if ($count > 0) $this->perPage = $count;
            $data = $this->allQuery($search)->orderBy('created_at', $order)->onlyTrashed()
                ->permission('managed by ' . $this->currentHighestRole()['name'])
                ->with('profile')->with('roles')->paginate($this->getPerPage());
            $data->transform(function ($item) use ($columns, $repo) {
                $repo->clearColumn($columns, $item);
                $repo->transformProfile($item);
                $repo->transformRoles($item);
                return $item;
            });
            $body->setBodyMessage($this->messageResponse['successGetTrashed']);
            $body->setBodyData($data);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /**
     * Create a new record
     *
     * @param array $input Data attribute
     * @param bool $author Create data with author status
     * @return BodyResponse
     */
    public function create(array $input, bool $author = false): BodyResponse
    {
        $input['username'] = $input['email'];
        $body = new BodyResponse();
        try {
            $validator = Validator::make(
                $input,
                $this->createValidation()->rules,
                $this->createValidation()->messages,
                $this->createValidation()->attributes
            );
            if ($validator->fails())
                return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            if ($author) $this->insertAuthor(true, $input);

            $this->model = $this->model->newInstance($input);
            $this->model->save();
            $body->setBodyMessage($this->messageResponse['successCreated']);
            $body->setBodyData($this->model);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /**
     * Reset current user password
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @param bool $author Update data with author status
     * @return BodyResponse
     */
    public function reset(string $column, string|int|float $value,  bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        try {
            $randomPassword = Str::random(8);
            $model = $this->findBy($column, $value);
            if (!$model) return $body->setResponseNotFound($this->messageResponseKey);
            if ($author) $this->insertAuthor(true, $model);

            $model->sendResetNotification($randomPassword);

            $model->password = Hash::make($randomPassword);
            $model->save();

            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }



    /**
     * Get record with profile
     *
     * @param string $column Where column
     * @param string|int|float $value Keyword value
     * @param bool $firstResult Get first result only
     * @param bool $withTrashed Get data with trashed data
     * @return BodyResponse
     */
    public function getWithProfile(string $column, string|int|float $value, bool $onlyTrashed = false): BodyResponse
    {
        $body = new BodyResponse();
        $repo = $this;
        try {
            if ($onlyTrashed) $model = $this->allQuery()->onlyTrashed()->where($column, '=', $value)->with('profile')->with('roles')->first();
            else $model = $this->allQuery()->where($column, '=', $value)->with('profile')->with('roles')->first();

            if (!$model) return $body->setResponseNotFound($this->messageResponseKey);
            $this->transformProfile($model);
            $this->transformRoles($model);

            $body->setBodyMessage($this->messageResponse['successGet']);
            $body->setBodyData($model);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /**
     * Update a record by where condition
     *
     * @param array $input Input value to be update
     * @param mixed $column Where column
     * @param mixed $value Where value
     * @param bool $author Update data with author status
     * @return BodyResponse
     */
    public function updateBy(array $input, string $column, string|int|float $value, bool $author = false): BodyResponse
    {
        $body = new BodyResponse();
        $user = User::where($column, '=', $value)->with('profile')->first();
        try {
            $validator = Validator::make(
                $input,
                $this->updateValidation()->rules,
                $this->updateValidation()->messages,
                $this->updateValidation()->attributes
            );
            if ($validator->fails())
                return $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);

            if (array_key_exists('profile_photo_url', $input) && $input['profile_photo_url']) {
                $olderFile = $user->profile->photo_url;
                if ($olderFile) Storage::disk('public')->delete($olderFile);

                $fileName = $input['profile_photo_url']->hashName();
                $filePath = 'user/' . $user->id . '/avatar/';
                $isUploaded = Storage::disk('public')
                    ->put($filePath . $fileName, File::get($input['profile_photo_url']->getRealPath()));
                if ($isUploaded) $input['profile_photo_url'] = $filePath . $fileName;
            }
            $user->profile->fill($this->filterProfileData($input));
            $user->profile->save();


            $model = $this->findBy($column, $value);
            if (!$model) return $body->setResponseNotFound($this->messageResponseKey);
            if ($author) $this->insertAuthor(true, $model);

            $model->fill($input);
            $model->save();

            $body->setBodyMessage($this->messageResponse['successUpdated']);
            $body->setBodyData($model);
        } catch (\Throwable $th) {
            $body->setException($th);
            $body->setResponseError($th->getMessage());
            $body->setBodyMessage($this->messageResponse['failedError']);
        }
        return $body;
    }

    /** ================================= Support Function Below ================================= */
    /**
     * Remove profile name prefix from input key name
     * @param array $input Request input array
     * @return array 
     */
    private function filterProfileData(array $input): array
    {
        $result = [];
        foreach ($input as $itemkey => $itemvalue) {
            $result[str_replace('profile_', '', $itemkey)] = $itemvalue;
        }
        return $result;
    }
}

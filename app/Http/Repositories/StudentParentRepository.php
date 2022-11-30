<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Http\Response\BodyResponse;
use App\Models\StudentParent;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentParentRepository extends BaseRepository
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
        return $this->model = StudentParent::class;
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
        try {
            if ($count > 0) $this->perPage = $count;
            $data = $this->allQuery($search)->hasPermissionTo('')->orderBy('created_at', $order)->with('profile')
                ->with('roles')->paginate($this->getPerPage());
            $data->transform(function ($item) use ($columns) {
                $profileData = $item->profile;
                $roleData = [];
                foreach ($item->roles as $role) {
                    $roleData[] = $role->name;
                }
                $this->clearColumn($columns, $item);
                $item->setAttribute('profile_gender', $profileData?->gender);
                $item->setAttribute('profile_photo_url', $profileData?->photo_url);
                $item->setAttribute('profile_phone', $profileData?->phone);
                $item->setAttribute('profile_birthday', $profileData?->birthday);
                $item->setAttribute('profile_address', $profileData?->address);
                unset($item->profile);
                unset($item->roles);
                $item->setAttribute('roles', $roleData);
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
        try {
            if ($count > 0) $this->perPage = $count;
            $data = $this->allQuery($search)->onlyTrashed()->orderBy('created_at', $order)->with('profile')->paginate($this->getPerPage(), $columns);
            $data->transform(function ($item) use ($columns) {
                $profileData = $item->profile;
                $roleData = [];
                foreach ($item->roles as $role) {
                    $roleData[] = $role->name;
                }
                $this->clearColumn($columns, $item);
                $item->setAttribute('profile_gender', $profileData?->gender);
                $item->setAttribute('profile_photo_url', $profileData?->photo_url);
                $item->setAttribute('profile_phone', $profileData?->phone);
                $item->setAttribute('profile_birthday', $profileData?->birthday);
                $item->setAttribute('profile_address', $profileData?->address);
                unset($item->profile);
                unset($item->roles);
                $item->setAttribute('roles', $roleData);
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
}

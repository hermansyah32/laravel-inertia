<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Base\BaseRepository;
use App\Http\Response\BodyResponse;
use App\Models\User;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
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
                'username' => ['nullable', 'string', 'max:255', 'unique:users'],
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

            $model->password = Hash::make($randomPassword);
            $model->save();

            $body->setBodyMessage($this->messageResponse['successUpdated']);
        } catch (\Throwable $th) {
            $body->setBodyMessage($this->messageResponse['failedError']);
            $body->setResponseError($th->getMessage());
        }
        return $body;
    }
}

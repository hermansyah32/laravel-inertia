<?php

namespace App\Http\Repositories\Base;

use App\Http\Response\BodyResponse;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

abstract class BaseAuthRepository extends BaseRepository
{
    public function __construct(Application $app)
    {
        parent::__construct($app, Lang::get('data.account'));
    }
    /**
     * Check if account already exists in every class
     * @return BodyResponse
     */
    protected function checkAccount(array $data): BodyResponse
    {
        $validator = Validator::make($data, $this->AccountRules());
        $body = new BodyResponse();
        if ($validator->fails()) {
            $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
            return $body;
        }
        return $body;
    }

    /**
     * Check if email already exists in every class
     *
     * @param string $email Email address
     * @return BodyResponse
     */
    protected function checkEmail(string $email): BodyResponse
    {
        $validator = Validator::make(['email' => $email], ['email' => $this->AccountRules()['email']]);
        $body = new BodyResponse();
        if ($validator->fails()) {
            $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
            return $body;
        }
        return $body;
    }

    /**
     * Check if username already exists in every class
     * @return BodyResponse
     */
    protected function checkUsername(string $username): BodyResponse
    {
        $validator = Validator::make(['username' => $username], ['username' => $this->AccountRules()['username']]);
        $body = new BodyResponse();
        if ($validator->fails()) {
            $body->setResponseValidationError($validator->errors(), $this->messageResponseKey);
            return $body;
        }
        return $body;
    }
}

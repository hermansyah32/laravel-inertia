<?php

namespace App\Http\API\Account;

use App\Http\Controllers\BaseAPIController as Controller;
use App\Http\Repositories\Base\BaseAccountRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /** @var  BaseAccountRepository */
    private $repository;

    /**
     * Class constructor
     * @param BaseAccountRepository $repo 
     * @return void 
     */
    public function __construct(BaseAccountRepository $repo)
    {
        $this->repository = $repo;
    }

    // Not used in authentication controller
    public function checkPermission($rule): bool|BodyResponse
    {
        return true;
    }

    public function permissionRule()
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $result = $this->repository->getProfile();
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }

        return $this->sendResponse($result);
    }

    /**
     * send email update request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendEmailUpdate(Request $request)
    {
        $result = $this->repository->sendEmailUpdate($request->email);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }

        return $this->sendResponse($result);
    }

    /**
     * Update the account email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {
        $result = $this->repository->updateEmail($request->email, $request->token);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }

        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $result = $this->repository->updateProfile($request->all());
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }

        return $this->sendResponse($result);
    }

    /**
     * Update the account password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $result = $this->repository->updatePassword($request->all());
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }

        return $this->sendResponse($result);
    }
}

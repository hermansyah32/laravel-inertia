<?php

namespace App\Http\API\Account;

use App\Http\Controllers\BaseAPIController as Controller;
use App\Http\Repositories\Base\BaseAccountRepository;
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

    // Not sued in authentication controller
    public function checkPermission($rule) { }
    public function permissionRule(){} 

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $result = $this->repository->getProfile();
        return $this->sendResponse($result);
    }

    /**
     * Update the account email or/and username.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $result = $this->repository->updateEmail($request->email);
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
        return $this->sendResponse($result);
    }
}

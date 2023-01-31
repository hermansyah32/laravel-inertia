<?php

namespace App\Http\Controllers\Account;

use App\Helper\Constants;
use App\Helper\FlashMessenger;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Repositories\Base\BaseAccountRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /** @var  BaseAccountRepository */
    private $repository;

    public function baseComponent()
    {
        return 'Account';
    }

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
     * Display the account profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showProfile(Request $request)
    {
        $result = $this->repository->getProfile();
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $this->saveLog($result);
            FlashMessenger::sendFromBody($result);
        }

        return Inertia::render($this->baseComponent() . '/Profile', [
            'account' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Edit the account profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $result = $this->repository->getProfile();
        FlashMessenger::sendFromBody($result);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $this->saveLog($result);
            return redirect()->back();
        }

        return Inertia::render($this->baseComponent() . '/Profile/Edit', [
            'account' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Update the account profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($input[$key] === null) {
                unset($input[$key]);
            }
        }
        $result = $this->repository->updateProfile($input);
        FlashMessenger::sendFromBody($result);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $this->saveLog($result);
            return redirect()->back()->withInput($input);
        }

        return redirect(route('profile'));
    }

    /**
     * Edit the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editSecurity(Request $request)
    {
        return Inertia::render($this->baseComponent() . '/Security', []);
    }

    /**
     * Update the account password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSecurity(Request $request)
    {
        $input = $request->all();
        foreach ($input as $key => $value) {
            if ($input[$key] === null) {
                unset($input[$key]);
            }
        }

        $result = $this->repository->updatePassword($input);
        FlashMessenger::sendFromBody($result);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $this->saveLog($result);
            return redirect()->back();
        }

        return redirect(route('account.security'));
    }

    /**
     * Send email change request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendEmailUpdate(Request $request)
    {
        $result = $this->repository->sendEmailUpdate($request->email);

        FlashMessenger::sendFromBody($result);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $this->saveLog($result);
            return redirect()->back();
        }
        return $this->sendResponse($result);
    }

    /**
     * Update email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {
        $result = $this->repository->updateEmail($request->email, $request->token);

        FlashMessenger::sendFromBody($result);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            $this->saveLog($result);
            return redirect()->back();
        }

        return $this->sendResponse($result);
    }
}

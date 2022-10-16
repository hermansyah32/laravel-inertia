<?php

namespace App\Http\API\Account;

use App\Http\Controllers\BaseController;
use App\Http\Repositories\Base\BaseAccountRepository;
use Illuminate\Http\Request;

class ProfileController extends BaseController
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $result = $this->repository->updateProfile($request->all());
        return $this->sendResponse($result);
    }
}

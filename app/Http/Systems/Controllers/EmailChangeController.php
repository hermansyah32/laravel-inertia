<?php

namespace App\Http\Systems\Controllers;

use App\Helper\FlashMessenger;
use App\Http\Laravel\Controller;
use App\Http\Repositories\Base\BaseAccountRepository;
use Illuminate\Http\Request;

class EmailChangeController extends Controller
{
    /** @var BaseAccountRepository */
    protected $repository;

    public function __construct(BaseAccountRepository $repo)
    {
        $this->repository = $repo;
    }

    public function sendEmailUpdateRequest(Request $request)
    {
        $body = $this->repository->sendEmailUpdate($request->email);
    }

    public function verifyRequest(Request $request)
    {
        $body = $this->repository->updateEmail($request->email, $request->token);;
    }
}

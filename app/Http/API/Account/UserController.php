<?php

namespace App\Http\API\Account;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** @var  UserRepository */
    private $repository;

    /**
     * Class constructor
     * @param UserRepository $repo 
     * @return void 
     */
    public function __construct(UserRepository $repo)
    {
        $this->repository = $repo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->index($order, $request->all(), $columns, $count);
        return $this->sendResponse($result);
    }

    /**
     * Display a listing of the resource trashed.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTrashed(Request $request)
    {
        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->indexTrashed($order, $request->all(), $columns, $count);
        return $this->sendResponse($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->repository->create($request->all());
        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $result = $this->repository->get('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource trashed.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showTrashed(int $id)
    {
        $result = $this->repository->getTrashed('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $result = $this->repository->updateBy($request->all(), 'id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, int $id)
    {
        $result = $this->repository->restoreBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $result = $this->repository->deleteBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Remove the specified resource from storage permanently.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function permanentDestroy(int $id)
    {
        $result = $this->repository->permanentDeleteBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Reset the specified users.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function reset(int $id)
    {
        $result = $this->repository->reset('id', $id);
        return $this->sendResponse($result);
    }
}

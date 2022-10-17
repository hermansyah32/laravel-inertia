<?php

namespace App\Http\API\Account;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Response\BodyResponse;
use Exception;
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

    public function permissionRule()
    {
        return ((object)[
            'index' => 'can index users',
            'indexTrashed' => 'can index trashed users',
            'get' => 'can get users',
            'getFull' => 'can get full users',
            'getTrashed' => 'can get trashed users',
            'update' => 'can update users',
            'restore' => 'can restore users',
            'destroy' => 'can destroy users',
            'permanentDestroy' => 'can permanentDestroy users',
            'reset' => 'can reset users'
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->index))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

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
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->indexTrashed))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }
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
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->store))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

        $result = $this->repository->create($request->all());
        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->show))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

        $result = $this->repository->get('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource trashed.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showTrashed(Request $request, int $id)
    {
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->showTrashed))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

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
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->update))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

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
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->restore))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

        $result = $this->repository->restoreBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->destroy))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

        $result = $this->repository->deleteBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Remove the specified resource from storage permanently.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function permanentDestroy(Request $request, int $id)
    {
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->permanentDestroy))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

        $result = $this->repository->permanentDeleteBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Reset the specified users.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request, int $id)
    {
        // Add permission checking
        try {
            if (!$request->user()->hashPermissionTo($this->permissionRule()->reset))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }

        $result = $this->repository->reset('id', $id);
        return $this->sendResponse($result);
    }
}

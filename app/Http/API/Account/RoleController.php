<?php

namespace App\Http\API\Account;

use App\Helper\Constants;
use App\Http\Controllers\BaseAPIController as Controller;
use App\Http\Repositories\RoleRepository;
use App\Http\Response\BodyResponse;
use Exception;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /** @var  RoleRepository */
    private $repository;

    /**
     * Class constructor
     * @param RoleRepository $repo 
     * @return void 
     */
    public function __construct(RoleRepository $repo)
    {
        $this->repository = $repo;
    }

    public function checkPermission($rule)
    {
        try {
            if (!$this->repository->currentAccount()
                ->hasPermissionTo($rule))
                throw new Exception('Permission denied');
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $this->sendResponse($body);
        }
    }

    public function permissionRule()
    {
        return Constants::PERMISSIONS()->roles;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->checkPermission($this->permissionRule()->index);

        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->index($order, $request->all(), $columns, $count);
        return $this->sendResponse($result);
    }

    /**
     * Display a listing of the resource trashed.
     ** @return \Illuminate\Http\Response
     */
    public function indexTrashed(Request $request)
    {
        // Add permission checking
        $this->checkPermission($this->permissionRule()->index_trashed);

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
        $this->checkPermission($this->permissionRule()->store);

        $result = $this->repository->create($request->all());
        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->show);

        $result = $this->repository->get('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Display the specified resource trashed.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function showTrashed(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->show_trashed);

        $result = $this->repository->getTrashed('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->update);

        $result = $this->repository->updateBy($request->all(), 'id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->restore);

        $result = $this->repository->restoreBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->destroy);

        $result = $this->repository->deleteBy('id', $id);
        return $this->sendResponse($result);
    }

    /**
     * Remove the specified resource from storage permanently.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function permanentDestroy(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->permanent_destroy);

        $result = $this->repository->permanentDeleteBy('id', $id);
        return $this->sendResponse($result);
    }
}

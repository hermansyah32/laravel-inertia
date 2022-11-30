<?php

namespace App\Http\Controllers\Apps\Assignment;

use App\Http\Controllers\AppsController as Controller;
use App\Http\Repositories\AssignmentGroupRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\SubjectAssignment;
use Exception;
use Illuminate\Http\Request;

class AssignmentGroupController extends Controller
{
    /** @var  AssignmentGroupRepository */
    private $repository;

    /**
     * Class constructor
     * @param AssignmentGroupRepository $repo 
     * @return void 
     */
    public function __construct(AssignmentGroupRepository $repo)
    {
        $this->repository = $repo;
    }

    public function baseComponent() { }

    public static function getPageItems() { }

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
        return ((object)[
            'index' => 'can index assignment groups',
            'indexTrashed' => 'can index trashed assignment groups',
            'show' => 'can show assignment groups',
            'showFull' => 'can show full assignment groups',
            'showTrashed' => 'can show trashed assignment groups',
            'store' => 'can store assignment groups',
            'update' => 'can update assignment groups',
            'restore' => 'can restore assignment groups',
            'destroy' => 'can destroy assignment groups',
            'permanentDestroy' => 'can permanent destroy assignment groups',
        ]);
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
        $this->checkPermission($this->permissionRule()->indexTrashed);

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $this->checkPermission($this->permissionRule()->show);

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
        $this->checkPermission($this->permissionRule()->showTrashed);

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
        $this->checkPermission($this->permissionRule()->update);

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
        $this->checkPermission($this->permissionRule()->restore);

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
        $countAssignment = SubjectAssignment::where('assignment_group_id', $id)->count();
        if ($countAssignment > 0) {
            $body = new BodyResponse();
            $body->setResponseError('Assignment group still have subject assignment', ResponseCode::SERVER_ERROR);
            return $this->sendResponse($body);
        }

        $this->checkPermission($this->permissionRule()->destroy);

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
        $this->checkPermission($this->permissionRule()->permanentDestroy);

        $result = $this->repository->permanentDeleteBy('id', $id);
        return $this->sendResponse($result);
    }
}

<?php

namespace App\Http\Controllers\Apps\Class;

use App\Http\Controllers\AppsController as Controller;
use App\Http\Repositories\StudentClassRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\StudentProfile;
use Exception;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    /** @var  StudentClassRepository */
    private $repository;

    public function baseComponent() {
        return 'Apps/StudentClass';
    }

    /**
     * Class constructor
     * @param StudentClassRepository $repo 
     * @return void 
     */
    public function __construct(StudentClassRepository $repo)
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
        return ((object)[
            'index' => 'can index student classes',
            'indexTrashed' => 'can index trashed student classes',
            'show' => 'can show student classes',
            'showFull' => 'can show full student classes',
            'showTrashed' => 'can show trashed student classes',
            'store' => 'can store student classes',
            'update' => 'can update student classes',
            'restore' => 'can restore student classes',
            'destroy' => 'can destroy student classes',
            'permanentDestroy' => 'can permanent destroy student classes',
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
        $countStudent = StudentProfile::where('class', $id)->count();
        if ($countStudent > 0) {
            $body = new BodyResponse();
            $body->setResponseError('Class still have students', ResponseCode::SERVER_ERROR);
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

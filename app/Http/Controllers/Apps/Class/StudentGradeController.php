<?php

namespace App\Http\Controllers\Apps\Class;

use App\Helper\Constants;
use App\Helper\FlashMessenger;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Repositories\StudentGradeRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use App\Models\StudentClass;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Exceptions\UnauthorizedException;

class StudentGradeController extends Controller
{
    public function baseComponent()
    {
        return 'Apps/StudentGrade';
    }

    /** @var  StudentGradeRepository */
    private $repository;

    /**
     * Class constructor
     * @param StudentGradeRepository $repo 
     * @return void 
     */
    public function __construct(StudentGradeRepository $repo)
    {
        $this->repository = $repo;
    }

    public function checkPermission($rule): bool|BodyResponse
    {
        try {
            $result = $this->repository->currentAccount()->can($rule);
            if (!$result) throw new UnauthorizedException(401, "You do not have required permission");
            return true;
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }
    }

    public function permissionRule()
    {
        return Constants::PERMISSIONS()->student_grades;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkPermission = $this->checkPermission($this->permissionRule()->index);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return redirect(route('auth.404'));
        }

        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->index($order, $request->all(), $columns, $count);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
        }

        return Inertia::render($this->baseComponent(), [
            '_data' => $result->getBodyData(),
        ]);
    }
    /**
     * Display a listing of the resource trashed.
     ** @return \Illuminate\Http\Response
     */
    public function indexTrashed(Request $request)
    {
        // Add permission checking
        $checkPermission = $this->checkPermission($this->permissionRule()->index_trashed);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return redirect(route('auth.404'));
        }

        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->indexTrashed($order, $request->all(), $columns, $count);
        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
        }

        return Inertia::render($this->baseComponent(), [
            '_data' => $result->getBodyData(),
        ]);
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
        $countClass = StudentClass::where('student_grade_id', $id)->count();
        if ($countClass > 0) {
            $body = new BodyResponse();
            $body->setResponseError('Grade still have classes', ResponseCode::SERVER_ERROR);
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

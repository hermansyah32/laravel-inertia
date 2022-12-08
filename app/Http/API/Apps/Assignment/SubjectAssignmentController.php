<?php

namespace App\Http\API\Apps\Assignment;

use App\Helper\Constants;
use App\Http\Controllers\BaseAPIController as Controller;
use App\Http\Repositories\SubjectAssignmentRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Exception;
use Illuminate\Http\Request;

class SubjectAssignmentController extends Controller
{
    /** @var  SubjectAssignmentRepository */
    private $repository;

    /**
     * Class constructor
     * @param SubjectAssignmentRepository $repo 
     * @return void 
     */
    public function __construct(SubjectAssignmentRepository $repo)
    {
        $this->repository = $repo;
    }

    public function checkPermission($rule): bool|BodyResponse
    {
        try {
            if (!$this->repository->currentAccount()
                ->hasPermissionTo($rule))
                throw new Exception('Permission denied');
            return true;
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            return $body;
        }
    }

    public function permissionRule()
    {
        return Constants::PERMISSIONS()->subject_assignments;
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
            return $this->sendResponse($checkPermission);
        }

        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->index($order, $request->all(), $columns, $count);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
        return $this->sendResponse($result);
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
            return $this->sendResponse($checkPermission);
        }

        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->indexTrashed($order, $request->all(), $columns, $count);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->store);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $input = $request->all();
        $input['options'] = json_encode($input['options']);
        $result = $this->repository->create($input);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->show);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $result = $this->repository->get('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->show_trashed);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $result = $this->repository->getTrashed('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->update);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $input = $request->all();
        $input['options'] = json_encode($input['options']);
        $result = $this->repository->updateBy($input, 'id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->restore);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $result = $this->repository->restoreBy('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->destroy);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $result = $this->repository->deleteBy('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
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
        $checkPermission = $this->checkPermission($this->permissionRule()->permanent_destroy);
        if ($checkPermission !== true) {
            $checkPermission->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($checkPermission);
            return $this->sendResponse($checkPermission);
        }

        $result = $this->repository->permanentDeleteBy('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            $result->setRequestInfo($request, $this->repository->currentAccount()->toArray());
            $this->saveLog($result);
        }
        return $this->sendResponse($result);
    }
}

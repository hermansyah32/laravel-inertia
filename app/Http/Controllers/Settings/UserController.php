<?php

namespace App\Http\Controllers\Settings;

use App\Helper\Constants;
use App\Helper\FlashMessenger;
use App\Http\Controllers\SettingsController as Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Spatie\Permission\Exceptions\UnauthorizedException;

class UserController extends Controller
{
    /** @var  UserRepository */
    private $repository;

    public function baseComponent()
    {
        return 'Settings/Users';
    }

    /**
     * Class constructor
     * @param UserRepository $repo 
     * @return void 
     */
    public function __construct(UserRepository $repo)
    {
        $this->repository = $repo;
    }

    public function checkPermission($rule)
    {
        try {
            $result = $this->repository->currentAccount()->can($rule);
            if (!$result) throw new UnauthorizedException(401, "You do not have required permission");
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            $body->setException($th);
            throw $th;
        }
    }

    public function permissionRule()
    {
        return Constants::PERMISSIONS()->users;
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
        $result = $this->repository->indexWithProfile($order, $request->all(), $columns, $count);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
        }
        return Inertia::render($this->baseComponent(), [
            'pageItems' => $this->getPageItems(),
            'data' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Display a listing of the resource trashed.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTrashed(Request $request)
    {
        $this->checkPermission($this->permissionRule()->indexTrashed);

        $order = $request->order ?? 'desc';
        $columns = $request->columns ?? ['*'];
        $count = $request->perPage ?? 0;
        $result = $this->repository->indexWithProfileTrashed($order, $request->all(), $columns, $count);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
        }

        return Inertia::render($this->baseComponent() . '/Trashed', [
            'pageItems' => $this->getPageItems(),
            'data' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Create a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->checkPermission($this->permissionRule()->store);

        return Inertia::render($this->baseComponent() . '/Create', [
            'pageItems' => $this->getPageItems(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
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

        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR) {
            throw ValidationException::withMessages($result->getBodyData()->toArray());
        }

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
            return redirect(route('settings.users.create'))->withInput($request->all());
        }

        return redirect(route('settings.users.index'));
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

        return Inertia::render($this->baseComponent() . '/Show', [
            'pageItems' => $this->getPageItems(),
            'data' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Display the specified resource trashed.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function showTrashed(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->showTrashed);

        $result = $this->repository->getTrashed('id', $id);

        return Inertia::render($this->baseComponent() . '/ShowTrashed', [
            'pageItems' => $this->getPageItems(),
            'data' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->show);

        $result = $this->repository->get('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
        }

        return Inertia::render($this->baseComponent() . '/Edit', [
            'pageItems' => $this->getPageItems(),
            'data' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
    }

    /**
     * Display the specified resource trashed.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function editTrashed(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->showTrashed);

        $result = $this->repository->getTrashed('id', $id);

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
        }

        return Inertia::render($this->baseComponent() . '/EditTrashed', [
            'pageItems' => $this->getPageItems(),
            'data' => $result->getBodyData(),
            'constants' => [
                'gender' => Constants::GENDER()
            ]
        ]);
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

        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR) {
            throw ValidationException::withMessages($result->getBodyData()->toArray());
        }

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
            return redirect(route('settings.users.create'))->withInput($request->all());
        }

        return redirect(route('settings.users.show', ['id' => $id]));
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

        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR) {
            throw ValidationException::withMessages($result->getBodyData()->toArray());
        }

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
            return redirect(route('settings.users.create'))->withInput($request->all());
        }
        return redirect(route('settings.users.show', ['id' => $id]));
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

        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR) {
            throw ValidationException::withMessages($result->getBodyData()->toArray());
        }

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
            return redirect(route('settings.users.create'))->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage permanently.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function permanentDestroy(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->permanentDestroy);

        $result = $this->repository->permanentDeleteBy('id', $id);

        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR) {
            throw ValidationException::withMessages($result->getBodyData()->toArray());
        }

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
            return redirect(route('settings.users.create'))->withInput($request->all());
        }
    }

    /**
     * Reset the specified users.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request, string $id)
    {
        $this->checkPermission($this->permissionRule()->reset);

        $result = $this->repository->reset('id', $id);

        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR) {
            throw ValidationException::withMessages($result->getBodyData()->toArray());
        }

        if ($result->getResponseCode() !== ResponseCode::OK) {
            FlashMessenger::sendFromBody($result);
            return redirect(route('settings.users.create'))->withInput($request->all());
        }
    }
}

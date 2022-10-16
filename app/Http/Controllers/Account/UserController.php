<?php

namespace App\Http\Controllers\Account;

use App\Helper\FlashMessenger;
use App\Helper\FlashType;
use App\Http\Controllers\BaseController as Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Response\ResponseCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        return Inertia::render('User/Index', ['data' => $result->getBodyResponse()]);
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        return Inertia::render('User/IndexTrashed', ['data' => $result->getBodyResponse()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Create');
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        FlashMessenger::sendFromBody($result, FlashType::BANNER);
        return Redirect::route('users.index');
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        return Inertia::render('Create/Show', ['data' => $result->getBodyResponse()]);
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        return Inertia::render('Create/Show', ['data' => $result->getBodyResponse()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $result = $this->repository->get('id', $id);
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        return Inertia::render('Create/Update', ['data' => $result->getBodyResponse()]);
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        FlashMessenger::sendFromBody($result, FlashType::BANNER);
        return Redirect::route('users.index');
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        FlashMessenger::sendFromBody($result, FlashType::BANNER);
        return Redirect::route('users.index');
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        FlashMessenger::sendFromBody($result, FlashType::BANNER);
        return Redirect::route('users.index');
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        FlashMessenger::sendFromBody($result, FlashType::BANNER);
        return Redirect::route('users.indexTrashed');
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
        if ($result->getResponseCode() === ResponseCode::VALIDATION_ERROR)
            throw ValidationException::withMessages($result->getBodyData());
        if ($result->getResponseCode() !== ResponseCode::OK)
            throw new Exception($result->getBodyMessage());

        FlashMessenger::sendFromBody($result, FlashType::BANNER);
        return Redirect::route('users.index');
    }
}

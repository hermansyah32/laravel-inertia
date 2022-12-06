<?php

namespace App\Http\Controllers;

use App\Http\Laravel\Controller as LaravelController;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

abstract class BaseAPIController extends LaravelController
{
    /**
     * Get permission rule
     * @return object 
     */
    abstract function permissionRule();

    public function checkPermission($rule)
    {
        try {
            $result = $this->repository->currentAccount()->hasPermissionTo($rule);
            if (!$result) throw new UnauthorizedException(ResponseCode::NOT_AUTHENTICATED->value, "You do not have required permission");
        } catch (\Throwable $th) {
            $body = new BodyResponse();
            $body->setPermissionDenied();
            $body->setException($th);
            throw $th;
        }
    }

    public function sendResponse(BodyResponse $body)
    {
        if (empty($body->getHeaderResponse()))
            return response()->json($body->getResponse(), $body->getResponseCode()->value);
        else
            return response()->json($body->getResponse(), $body->getResponseCode()->value, $body->getHeaderResponse());
    }

    public function saveLog(BodyResponse $body){
        if (!($body->getException() instanceof Exception)) return;

        $exception = $body->getException();
        Log::error($exception->getMessage());
    }
}

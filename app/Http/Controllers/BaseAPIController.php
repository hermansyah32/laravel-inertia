<?php

namespace App\Http\Controllers;

use App\Http\Laravel\Controller as LaravelController;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Throwable;

abstract class BaseAPIController extends LaravelController
{
    /**
     * Get permission rule
     * @return object 
     */
    abstract function permissionRule();

    abstract function checkPermission($rule): bool|BodyResponse;

    public function sendResponse(BodyResponse $body)
    {
        if (empty($body->getHeaderResponse()))
            return response()->json($body->getResponse(), $body->getResponseCode()->value);
        else
            return response()->json($body->getResponse(), $body->getResponseCode()->value, $body->getHeaderResponse());
    }

    public function saveLog(BodyResponse $body)
    {
        if (!($body->getException() instanceof Throwable)) return;
        if (config('app.debug')){
            dd($body);
        }

        $exception = $body->getException();

        Log::error($exception->getMessage(), $body->getRequestInfo());
        Log::error($exception->getMessage(), ['trace' =>  $exception->getTraceAsString()]);
    }
}

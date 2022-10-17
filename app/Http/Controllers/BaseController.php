<?php

namespace App\Http\Controllers;

use App\Http\Laravel\Controller as LaravelController;
use App\Http\Response\BodyResponse;

abstract class BaseController extends LaravelController
{
    /**
     * Get permission rule
     * @return object 
     */
    abstract function permissionRule();

    public function sendResponse(BodyResponse $body)
    {
        if (empty($body->getHeaderResponse()))
            return response()->json($body->getResponse(), $body->getResponseCode()->value);
        else
            return response()->json($body->getResponse(), $body->getResponseCode()->value, $body->getHeaderResponse());
    }
}

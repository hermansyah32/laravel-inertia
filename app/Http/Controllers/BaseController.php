<?php

namespace App\Http\Controllers;

use App\Http\Response\BodyResponse;
use Exception;
use Illuminate\Support\Facades\Log;

abstract class BaseController extends BaseAPIController
{

    abstract function baseComponent();

    abstract static function getPageItems();
}

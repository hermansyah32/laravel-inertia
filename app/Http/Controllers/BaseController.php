<?php

namespace App\Http\Controllers;

use Router\Navigation\Router;

abstract class BaseController extends BaseAPIController
{

    abstract function baseComponent();

    public function getNavigationRoute($auth = null)
    {
        return new Router($auth);
    }
}

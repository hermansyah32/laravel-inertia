<?php

namespace App\Http\Controllers;

use App\Http\Response\BodyResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RedirectRouteController extends BaseController
{

    public function baseComponent()
    {
        return 'Render';
    }

    public static function getPageItems()
    {
        // return DashboardController::getPageItems();
        return [];
    }

    public function permissionRule()
    {
    }

    public function checkPermission($rule): bool|BodyResponse
    {
        return true;
    }

    public function notFound(BodyResponse $body)
    {
        return Inertia::render($this->baseComponent() . '/NotFound', []);
    }
}

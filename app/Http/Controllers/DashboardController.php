<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

abstract class DashboardController extends Controller
{
    public static function getPageItems()
    {
        return [
            ['label' => Lang::get('Overview'), 'url' => route('dashboard'), 'icon' => 'ViewGridIcon', 'component' => 'Dashboard/Overview', 'show' => true]
        ];
    }
}

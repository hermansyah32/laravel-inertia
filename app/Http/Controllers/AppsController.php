<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

abstract class AppsController extends Controller
{
    public static function getPageItems()
    {
        return [
            ['label' => Lang::get('Student Grade'), 'url' => route('apps.grade'), 'icon' => 'ViewGridIcon', 'component' => 'Apps/StudentGrade', 'show' => true]
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

abstract class SettingsController extends Controller
{
    public static function getPageItems()
    {
        return [
            ['label' => Lang::get('User'), 'url' => route('settings.users.index'), 'icon' => 'UserIcon', 'component' => 'Settings/Users', 'show' => true],
            // ['label' => Lang::get('Role'), 'url' => route('settings.roles'), 'icon' => 'UserGroupIcon', 'component' => 'Settings/Role', 'show' => true],
            // ['label' => Lang::get('Permission'), 'url' => route('settings.permission'), 'icon' => 'KeyIcon', 'component' => 'Settings/Permission', 'show' => true]
        ];
    }
}

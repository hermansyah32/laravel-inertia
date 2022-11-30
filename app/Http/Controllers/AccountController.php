<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController as Controller;
use Illuminate\Support\Facades\Lang;

abstract class AccountController extends Controller
{
    public static function getPageItems()
    {
        return [
            ['label' => Lang::get('Profile'), 'url' => route('profile'), 'icon' => 'UserIcon', 'component' => 'Account/Profile', 'show' => true],
            ['label' => Lang::get('Profile'), 'url' => route('profile.edit'), 'icon' => 'UserIcon', 'component' => 'Account/Profile/Edit'],
            ['label' => Lang::get('Security'), 'url' => route('account.security'), 'icon' => 'ShieldCheckIcon', 'component' => 'Account/Security', 'show' => true],
        ];
    }
}

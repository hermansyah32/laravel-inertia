<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Router\Navigation\Router;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed[]
     */
    public function share(Request $request)
    {
        $auth = $request->user();

        if ($auth) {
            $auth = User::where('id', $auth->id)->with('profile')->with('roles')->first();
            $profileData = $auth->profile;
            $role = is_array($auth->roles) && count($auth->roles) > 0 ? $auth->roles[0]->name : null;
            $auth->setAttribute('profile_gender', $profileData?->gender);
            $auth->setAttribute('profile_photo_url', $profileData?->photo_url);
            $auth->setAttribute('profile_phone', $profileData?->phone);
            $auth->setAttribute('profile_birthday', $profileData?->birthday);
            $auth->setAttribute('profile_address', $profileData?->address);
            $auth->setAttribute('role', $role);
            unset($auth->profile);
            unset($auth->roles);
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $auth,
            ],
            'ziggy' => function () use ($request) {
                return [
                    'location' => $request->url(),
                ];
            },
            'flash' => [
                'title' => session()->get('flash_title'),
                'message' => session()->get('flash_message'),
                'notification' => session()->get('flash_notification'),
                'type' => session()->get('flash_type'),
            ],
            'navigationRoutes' => (new Router($auth))->getAll()
        ]);
    }
}

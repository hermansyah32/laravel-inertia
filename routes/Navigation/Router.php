<?php

namespace Router\Navigation;

use Illuminate\Support\Facades\Lang;

class Router
{
    private $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    private function filterRoute(&$routes)
    {
        foreach ($routes as $key => $route) {
            if (!$this->auth || !$this->auth->can('do ' . $route['route'])) {
                unset($routes[$key]);
            }
        }
    }

    public function getDashboard()
    {
        $routes = [
            ['label' => Lang::get('Overview'), 'route' => route('dashboard'), 'icon' => 'ViewGridIcon', 'component' => 'Dashboard/Overview', 'show' => true]
        ];

        $this->filterRoute($routes);
        return [
            'label' => 'Dashboard',
            'icon' => 'PresentationChartLineIcon',
            'route' => $routes
        ];
    }

    public function getSetting()
    {
        $routes = [
            ['label' => Lang::get('User'), 'route' => route('settings.users.index'), 'icon' => 'UserIcon', 'component' => 'Settings/Users', 'show' => true],
            // ['label' => Lang::get('Role'), 'route' => route('settings.roles'), 'icon' => 'UserGroupIcon', 'component' => 'Settings/Role', 'show' => true],
            // ['label' => Lang::get('Permission'), 'route' => route('settings.permission'), 'icon' => 'KeyIcon', 'component' => 'Settings/Permission', 'show' => true]
        ];

        $this->filterRoute($routes);
        return [
            'label' => 'Settings',
            'icon' => 'CogIcon',
            'route' => $routes
        ];
    }
    public function getApp()
    {
        $routes = [
            ['label' => Lang::get('Student Grade'), 'route' => route('apps.studentgrades'), 'icon' => 'ViewGridIcon', 'component' => 'Apps/StudentGrade', 'show' => true],
        ];

        $this->filterRoute($routes);
        return [
            'label' => 'Applications',
            'icon' => 'PuzzleIcon',
            'route' => $routes
        ];
    }

    public function getAll()
    {
        return [
            $this->getDashboard(),
            $this->getApp(),
            $this->getSetting()
        ];
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\DashboardController as Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function baseComponent()
    {
        return 'Dashboard/Overview';
    }

    // Not used in authentication controller
    public function checkPermission($rule)
    {
        // Empty
    }
    public function permissionRule()
    {
        // Empty
    }

    /**
     * Show overview.
     *
     * @return \Illuminate\Http\Response
     */
    public function overview(Request $request)
    {
        return Inertia::render($this->baseComponent(), [
            'pageItems' => $this->getPageItems()
        ]);
    }
}

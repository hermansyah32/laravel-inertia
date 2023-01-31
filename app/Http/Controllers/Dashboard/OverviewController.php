<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\BaseController as Controller;
use App\Http\Response\BodyResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OverviewController extends Controller
{
    public function baseComponent()
    {
        return 'Dashboard/Overview';
    }

    // Not used in authentication controller
    public function checkPermission($rule): bool|BodyResponse
    {
        return true;
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
            
        ]);
    }
}

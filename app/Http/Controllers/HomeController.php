<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Dashboard\DashboardService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    private DashboardService $dashboardService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->middleware('auth');
        $this->dashboardService = $dashboardService;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $summary = $this->dashboardService->summary(Auth::user());
        $recentWorkReports = $this->dashboardService
            ->recentWorkReports(Auth::user());
        return view(
            'home',
            compact(
                'summary',
                'recentWorkReports'
            )
        );
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        $user = $this->authUser();
        
        $totalTasks = $user->tasks()->count();
        $pendingTasks = $user->tasks()->where('status', 'pending')->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $recentTasks = $user->tasks()->latest()->take(5)->get();

        return view('dashboard', compact('totalTasks', 'pendingTasks', 'completedTasks', 'recentTasks'));
    }

    /**
     * Get dashboard statistics for API.
     *
     * @return JsonResponse
     */
    public function stats(): JsonResponse
    {
        $user = $this->authUser();
        
        $totalTasks = $user->tasks()->count();
        $pendingTasks = $user->tasks()->where('status', 'pending')->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();

        return response()->json([
            'total' => $totalTasks,
            'pending' => $pendingTasks,
            'completed' => $completedTasks,
        ]);
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Lead;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $withEmail = Lead::whereNotNull('email')->where('email', '!=', '')->count();
        $withWhatsapp = Lead::whereNotNull('whatsapp')->where('whatsapp', '!=', '')->count();
        $withWebsite = Lead::whereNotNull('website')->where('website', '!=', '')->count();
        $avgRating = Lead::whereNotNull('rating')->avg('rating');
        $categories = Lead::distinct()->pluck('category')->filter()->count();

        $recentActivities = ActivityLog::with(['user', 'lead'])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.dashboard', compact(
            'totalLeads', 'withEmail', 'withWhatsapp', 'withWebsite', 'avgRating', 'categories', 'recentActivities'
        ));
    }
}

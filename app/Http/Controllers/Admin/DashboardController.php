<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $withEmail = Lead::whereNotNull('email')->where('email', '!=', '')->count();
        $withWhatsapp = Lead::whereNotNull('whatsapp')->where('whatsapp', '!=', '')->count();
        $withPhone = Lead::whereNotNull('phone')->where('phone', '!=', '')->count();
        $withWebsite = Lead::whereNotNull('website')->where('website', '!=', '')->count();
        $avgRating = Lead::whereNotNull('rating')->avg('rating');
        $categories = Lead::distinct()->pluck('category')->filter()->sort()->values();

        $leadsToday = Lead::whereDate('created_at', today())->count();
        $leadsThisWeek = Lead::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $leadsThisMonth = Lead::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        $qualityStats = Lead::select('website_quality', DB::raw('count(*) as total'))
            ->whereNotNull('website_quality')->where('website_quality', '!=', '')
            ->groupBy('website_quality')->pluck('total', 'website_quality');

        $contactStats = Lead::select('contact_status', DB::raw('count(*) as total'))
            ->whereNotNull('contact_status')->where('contact_status', '!=', '')
            ->groupBy('contact_status')->pluck('total', 'contact_status');

        $categoryStats = Lead::select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')->where('category', '!=', '')
            ->groupBy('category')->orderByDesc('total')->take(10)->get();

        $areaCount = Lead::whereNotNull('area')->where('area', '!=', '')
            ->distinct('area')->count('area');

        $areaStats = Lead::select('area', DB::raw('count(*) as total'))
            ->whereNotNull('area')->where('area', '!=', '')
            ->groupBy('area')->orderByDesc('total')->take(10)->get();

        $topCategoryPerArea = [];
        foreach ($areaStats as $stat) {
            $top = Lead::where('area', $stat->area)
                ->whereNotNull('category')->where('category', '!=', '')
                ->select('category', DB::raw('count(*) as total'))
                ->groupBy('category')->orderByDesc('total')->first();
            $topCategoryPerArea[$stat->area] = $top?->category ?? '-';
        }

        $topRated = Lead::whereNotNull('rating')->orderByDesc('rating')->take(5)->get();

        $recentActivities = ActivityLog::with(['user', 'lead'])
            ->latest()->take(20)->get();

        $missingEmail = Lead::whereNull('email')->orWhere('email', '')->count();
        $missingWebsite = Lead::whereNull('website')->orWhere('website', '')->count();
        $missingWhatsapp = Lead::whereNull('whatsapp')->orWhere('whatsapp', '')->count();

        return view('admin.dashboard', compact(
            'totalLeads', 'withEmail', 'withWhatsapp', 'withPhone', 'withWebsite',
            'avgRating', 'categories', 'leadsToday', 'leadsThisWeek', 'leadsThisMonth',
            'qualityStats', 'contactStats', 'categoryStats', 'areaCount', 'areaStats', 'topCategoryPerArea', 'topRated',
            'recentActivities', 'missingEmail', 'missingWebsite', 'missingWhatsapp'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\OsmService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    protected OsmService $osmService;

    public function __construct(OsmService $osmService)
    {
        $this->osmService = $osmService;
    }

    public function index(Request $request)
    {
        if ($request->has('fetch_osm') && $request->filled('area') && $request->filled('type')) {
            try {
                $apiKey = config('services.google_places.api_key');
                $leads = $this->osmService->searchAndSave($request->area, $request->type, $apiKey);

                $savedCount = 0;
                $skippedCount = 0;
                foreach ($leads as $leadData) {
                    if (empty($leadData['company_name'])) {
                        continue;
                    }

                    $leadData['category'] = $request->type;

                    $exists = Lead::where('company_name', $leadData['company_name'])
                        ->where('area', $leadData['area'])
                        ->exists();

                    if (!$exists) {
                        Lead::create($leadData);
                        $savedCount++;
                    } else {
                        $skippedCount++;
                    }
                }

                $source = $apiKey ? 'Google Places' : 'OpenStreetMap';
                $message = "{$savedCount} new leads added from {$source}.";
                if ($skippedCount > 0) {
                    $message .= " ({$skippedCount} duplicates skipped)";
                }

                return redirect()->route('leads.index', [
                    'area' => $request->area,
                    'type' => $request->type,
                ])->with('success', $message);

            } catch (\Exception $e) {
                return redirect()->route('leads.index', [
                    'area' => $request->area,
                    'type' => $request->type,
                ])->with('error', 'Search failed: ' . $e->getMessage());
            }
        }

        $query = Lead::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('filter_category')) {
            $query->where('category', $request->filter_category);
        }

        if ($request->filled('filter_phone')) {
            if ($request->filter_phone === 'yes') {
                $query->whereNotNull('phone')->where('phone', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('phone')->orWhere('phone', '');
                });
            }
        }

        if ($request->filled('filter_website')) {
            if ($request->filter_website === 'yes') {
                $query->whereNotNull('website')->where('website', '!=', '');
            } else {
                $query->where(function ($q) {
                    $q->whereNull('website')->orWhere('website', '');
                });
            }
        }

        if ($request->filled('filter_rating')) {
            $query->where('rating', '>=', $request->filter_rating);
        }

        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('company_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('company_name', 'desc');
                break;
            case 'rating_high':
                $query->orderByRaw('rating IS NULL')->orderBy('rating', 'desc');
                break;
            case 'rating_low':
                $query->orderByRaw('rating IS NULL')->orderBy('rating', 'asc');
                break;
            case 'reviews':
                $query->orderByRaw('total_ratings IS NULL')->orderBy('total_ratings', 'desc');
                break;
            default:
                $query->latest();
        }

        $leads = $query->paginate(15)->withQueryString();
        $categories = Lead::distinct()->pluck('category')->filter()->sort()->values();

        return view('leads.index', [
            'leads' => $leads,
            'categories' => $categories,
            'filters' => $request->only([
                'area', 'type', 'search', 'filter_category',
                'filter_phone', 'filter_website', 'filter_rating', 'sort_by',
            ]),
            'hasApiKey' => (bool) config('services.google_places.api_key'),
        ]);
    }

    public function show(Lead $lead)
    {
        return view('leads.show', ['lead' => $lead]);
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function updateQuality(Lead $lead)
    {
        $lead->update([
            'website_quality' => request('website_quality'),
        ]);
        return response()->json(['success' => true]);
    }

    public function updateContact(Lead $lead)
    {
        $lead->update([
            'contact_status' => request('contact_status'),
        ]);
        return response()->json(['success' => true]);
    }

    public function updateEmail(Lead $lead)
    {
        $lead->update([
            'email' => request('email'),
        ]);
        return response()->json(['success' => true]);
    }
}

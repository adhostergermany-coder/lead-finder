@extends('admin.layout')

@section('title', 'Dashboard - Lead Finder')
@section('page_title', 'Dashboard')

@section('content')
<div class="mx-4">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Leads</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalLeads }}</p>
            <div class="flex gap-3 mt-2 text-xs text-gray-500">
                <span>Today: <strong>{{ $leadsToday }}</strong></span>
                <span>Week: <strong>{{ $leadsThisWeek }}</strong></span>
                <span>Month: <strong>{{ $leadsThisMonth }}</strong></span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Avg Rating</p>
            <p class="text-3xl font-bold text-yellow-500">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</p>
            <p class="text-xs text-gray-500 mt-2">{{ $categories->count() }} Categories</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">With Contact</p>
            <p class="text-3xl font-bold text-gray-800">{{ $withEmail + $withWhatsapp + $withPhone }}</p>
            <div class="flex gap-3 mt-2 text-xs text-gray-500">
                <span>Email: <strong>{{ $withEmail }}</strong></span>
                <span>WA: <strong>{{ $withWhatsapp }}</strong></span>
                <span>Phone: <strong>{{ $withPhone }}</strong></span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Missing Data</p>
            <p class="text-3xl font-bold text-red-500">{{ $missingEmail + $missingWebsite + $missingWhatsapp }}</p>
            <div class="flex gap-3 mt-2 text-xs text-gray-500">
                <span>No Email: <strong>{{ $missingEmail }}</strong></span>
                <span>No WA: <strong>{{ $missingWhatsapp }}</strong></span>
                <span>No Web: <strong>{{ $missingWebsite }}</strong></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        {{-- Website Quality --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Website Quality</h3>
            </div>
            <div class="p-5 space-y-2">
                @php $qualityColors = ['Good' => '#22c55e', 'Average' => '#eab308', 'Bad' => '#ef4444', 'Error' => '#6b7280']; @endphp
                @foreach(['Good', 'Average', 'Bad', 'Error'] as $q)
                    @php $count = $qualityStats[$q] ?? 0; $pct = $totalLeads > 0 ? round($count / $totalLeads * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm">
                            <span>{{ $q }}</span>
                            <span class="font-medium">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $qualityColors[$q] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Contact Status --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Contact Status</h3>
            </div>
            <div class="p-5 space-y-2">
                @php $contactColors = ['Mail' => '#3b82f6', 'WhatsApp' => '#22c55e', 'SMS' => '#eab308']; @endphp
                @foreach(['Mail', 'WhatsApp', 'SMS'] as $c)
                    @php $count = $contactStats[$c] ?? 0; $pct = $totalLeads > 0 ? round($count / $totalLeads * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm">
                            <span>{{ $c }}</span>
                            <span class="font-medium">{{ $count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: {{ $contactColors[$c] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top Categories --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Top Categories</h3>
            </div>
            <div class="p-5 space-y-2">
                @foreach($categoryStats as $cat)
                    @php $pct = $totalLeads > 0 ? round($cat->total / $totalLeads * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="truncate">{{ $cat->category }}</span>
                            <span class="font-medium">{{ $cat->total }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="h-2 rounded-full" style="width: {{ $pct }}%; background-color: #6366f1"></div>
                        </div>
                    </div>
                @endforeach
                @if($categoryStats->count() == 0)
                    <p class="text-gray-400 text-sm">No categories yet.</p>
                @endif
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Top Rated --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Top Rated Leads</h3>
            </div>
            <div class="overflow-x-auto">
                @if($topRated->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reviews</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($topRated as $lead)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm">{{ $lead->company_name }}</td>
                                    <td class="px-4 py-2 text-sm text-yellow-500 font-semibold">★ {{ number_format($lead->rating, 1) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $lead->total_ratings ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-5 py-8 text-center text-gray-400">No ratings yet.</div>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-5 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800">Recent Activity</h3>
            </div>
            <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
                @if($recentActivities->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Lead</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Field</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($recentActivities as $a)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm">{{ $a->user->name }}</td>
                                    <td class="px-4 py-2 text-sm max-w-[120px] truncate">{{ $a->lead->company_name }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $a->field }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-400 whitespace-nowrap">{{ $a->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-5 py-8 text-center text-gray-400">No activity yet.</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

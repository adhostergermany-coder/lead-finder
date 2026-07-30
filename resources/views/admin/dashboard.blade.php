@extends('admin.layout')

@section('title', 'Dashboard - Lead Finder')
@section('page_title', 'Dashboard')

@section('content')
<div class="mx-4">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Leads</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalLeads }}</p>
            <div class="flex gap-2 mt-1 text-xs text-gray-500">
                <span>Today: <strong>{{ $leadsToday }}</strong></span>
                <span>Week: <strong>{{ $leadsThisWeek }}</strong></span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Avg Rating</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $categories->count() }} Categories &bull; {{ $areaCount }} Areas</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide">With Contact</p>
            <p class="text-2xl font-bold text-gray-800">{{ $withEmail + $withWhatsapp + $withPhone }}</p>
            <div class="flex gap-2 mt-1 text-xs text-gray-500">
                <span>Email: <strong>{{ $withEmail }}</strong></span>
                <span>WA: <strong>{{ $withWhatsapp }}</strong></span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Missing</p>
            <p class="text-2xl font-bold text-red-500">{{ $missingEmail + $missingWebsite + $missingWhatsapp }}</p>
            <div class="flex gap-2 mt-1 text-xs text-gray-500">
                <span>No Email: <strong>{{ $missingEmail }}</strong></span>
                <span>No Web: <strong>{{ $missingWebsite }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Charts + Tables Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        {{-- Quality & Contact --}}
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="font-semibold text-gray-800 text-sm mb-3">Website Quality</h3>
            <div class="flex gap-6">
                <div class="w-1/2">
                    <canvas id="qualityChart" height="120"></canvas>
                </div>
                <div class="w-1/2 space-y-2">
                    @php $ql = ['Good' => '#22c55e', 'Average' => '#eab308', 'Bad' => '#ef4444', 'Error' => '#6b7280']; @endphp
                    @foreach(['Good', 'Average', 'Bad', 'Error'] as $q)
                        @php $cnt = $qualityStats[$q] ?? 0; @endphp
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $ql[$q] }}"></span>
                            <span class="text-gray-600 w-14">{{ $q }}</span>
                            <span class="font-medium">{{ $cnt }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="font-semibold text-gray-800 text-sm mb-3">Contact Status</h3>
            <div class="flex gap-6 items-center">
                <div class="w-28">
                    <canvas id="contactChart" height="100"></canvas>
                </div>
                <div class="space-y-2">
                    @php $cl = ['Mail' => '#3b82f6', 'WhatsApp' => '#22c55e', 'SMS' => '#eab308']; @endphp
                    @foreach(['Mail', 'WhatsApp', 'SMS'] as $c)
                        @php $cnt = $contactStats[$c] ?? 0; @endphp
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $cl[$c] }}"></span>
                            <span class="text-gray-600 w-20">{{ $c }}</span>
                            <span class="font-medium">{{ $cnt }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Categories + Areas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow-sm p-4">
            <h3 class="font-semibold text-gray-800 text-sm mb-3">Top Categories</h3>
            <canvas id="categoryChart" height="100"></canvas>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-gray-800 text-sm">Areas / Countries</h3>
                <span class="text-xs text-gray-400">{{ $areaCount }} total</span>
            </div>
            <canvas id="areaChart" height="100"></canvas>
        </div>
    </div>

    {{-- Top Rated + Recent Activity --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Top Rated Leads</h3>
            </div>
            @if($topRated->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($topRated as $lead)
                        <div class="px-4 py-2.5 flex justify-between items-center text-sm">
                            <span class="text-gray-700 truncate">{{ $lead->company_name }}</span>
                            <span class="text-yellow-500 font-semibold ml-2 whitespace-nowrap">★ {{ number_format($lead->rating, 1) }} ({{ $lead->total_ratings ?? 0 }})</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-4 py-6 text-center text-gray-400 text-sm">No ratings yet.</div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800 text-sm">Recent Activity</h3>
            </div>
            <div class="max-h-[240px] overflow-y-auto divide-y divide-gray-100">
                @forelse($recentActivities as $a)
                    <div class="px-4 py-2 text-xs">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">{{ $a->user->name }}</span>
                            <span class="text-gray-400">{{ $a->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-gray-500 mt-0.5">
                            <span class="font-medium">{{ $a->lead->company_name }}</span> &mdash; {{ $a->field }}:
                            <span class="text-gray-400">{{ Str::limit($a->old_value, 20) }}</span>
                            <span class="text-gray-300"> &rarr; </span>
                            <span class="text-gray-600">{{ Str::limit($a->new_value, 20) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-gray-400 text-sm">No activity yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@php
$qualityLabelsJs = json_encode(['Good', 'Average', 'Bad', 'Error']);
$qualityDataJs = json_encode([$qualityStats['Good'] ?? 0, $qualityStats['Average'] ?? 0, $qualityStats['Bad'] ?? 0, $qualityStats['Error'] ?? 0]);
$qualityColorsJs = json_encode(['#22c55e', '#eab308', '#ef4444', '#6b7280']);

$contactLabelsJs = json_encode(['Mail', 'WhatsApp', 'SMS']);
$contactDataJs = json_encode([$contactStats['Mail'] ?? 0, $contactStats['WhatsApp'] ?? 0, $contactStats['SMS'] ?? 0]);
$contactColorsJs = json_encode(['#3b82f6', '#22c55e', '#eab308']);

$catLabels = []; $catData = [];
foreach ($categoryStats as $c) { $catLabels[] = $c->category; $catData[] = $c->total; }
$catLabelsJs = json_encode($catLabels);
$catDataJs = json_encode($catData);

$areaLabels = []; $areaData = [];
foreach ($areaStats as $s) { $areaLabels[] = $s->area; $areaData[] = $s->total; }
$areaLabelsJs = json_encode($areaLabels);
$areaDataJs = json_encode($areaData);
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('qualityChart'), {
        type: 'bar',
        data: { labels: {!! $qualityLabelsJs !!}, datasets: [{ label: '', data: {!! $qualityDataJs !!}, backgroundColor: {!! $qualityColorsJs !!}, borderRadius: 4 }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false }, tooltip: { enabled: true } }, scales: { x: { display: false }, y: { display: false, beginAtZero: true } } }
    });

    new Chart(document.getElementById('contactChart'), {
        type: 'doughnut',
        data: { labels: {!! $contactLabelsJs !!}, datasets: [{ data: {!! $contactDataJs !!}, backgroundColor: {!! $contactColorsJs !!}, borderWidth: 0 }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false }, tooltip: { enabled: true } }, cutout: '65%' }
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: { labels: {!! $catLabelsJs !!}, datasets: [{ label: '', data: {!! $catDataJs !!}, backgroundColor: '#6366f1', borderRadius: 4 }] },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, scales: { x: { ticks: { font: { size: 10 } } }, y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } } } }
    });

    new Chart(document.getElementById('areaChart'), {
        type: 'bar',
        data: { labels: {!! $areaLabelsJs !!}, datasets: [{ label: '', data: {!! $areaDataJs !!}, backgroundColor: '#8b5cf6', borderRadius: 4 }] },
        options: { responsive: true, maintainAspectRatio: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } }, y: { ticks: { font: { size: 10 } } } } }
    });
});
</script>
@endsection

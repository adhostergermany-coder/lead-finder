@extends('admin.layout')

@section('title', 'Dashboard - Lead Finder')
@section('page_title', 'Dashboard')

@section('content')
<div class="mx-4">
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Leads</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalLeads }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">With Email</p>
            <p class="text-3xl font-bold text-gray-800">{{ $withEmail }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">With WhatsApp</p>
            <p class="text-3xl font-bold text-gray-800">{{ $withWhatsapp }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">With Website</p>
            <p class="text-3xl font-bold text-gray-800">{{ $withWebsite }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Avg Rating</p>
            <p class="text-3xl font-bold text-gray-800">{{ $avgRating ? number_format($avgRating, 1) : '-' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Categories</p>
            <p class="text-3xl font-bold text-gray-800">{{ $categories }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
        </div>
        <div class="overflow-x-auto">
            @if($recentActivities->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Lead</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Field</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Old Value</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">New Value</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentActivities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm">{{ $activity->user->name }}</td>
                                <td class="px-4 py-2 text-sm">{{ $activity->lead->company_name }}</td>
                                <td class="px-4 py-2 text-sm font-medium">{{ $activity->field }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500 max-w-[200px] break-words">{{ $activity->old_value }}</td>
                                <td class="px-4 py-2 text-sm text-gray-500 max-w-[200px] break-words">{{ $activity->new_value }}</td>
                                <td class="px-4 py-2 text-sm text-gray-400 whitespace-nowrap">{{ $activity->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-8 text-center text-gray-400">
                    No activity yet. Make changes to leads to see them here.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

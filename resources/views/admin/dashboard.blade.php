@extends('admin.layout')

@section('title', 'Dashboard - Lead Finder')
@section('page_title', 'Dashboard')

@section('content')
<div class="mx-4">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Leads</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalLeads }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">With Email</p>
            <p class="text-3xl font-bold text-gray-800">{{ $withEmail }}</p>
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
</div>
@endsection

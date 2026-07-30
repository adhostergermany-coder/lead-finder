@extends('admin.layout')

@section('title', $lead->company_name . ' - Lead Finder')
@section('page_title', $lead->company_name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $lead->company_name }}</h1>

        <div class="space-y-4">
            <div>
                <label class="text-sm text-gray-500">Category</label>
                <p class="font-medium">{{ $lead->category ?: '-' }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Phone</label>
                <p>
                    @if($lead->phone)
                        <a href="tel:{{ $lead->phone }}" class="text-green-600 hover:underline font-medium">{{ $lead->phone }}</a>
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Email</label>
                <p>
                    @if($lead->email)
                        <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:underline font-medium">{{ $lead->email }}</a>
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Website</label>
                <p>
                    @if($lead->website)
                        <a href="{{ $lead->website }}" target="_blank" class="text-indigo-600 hover:underline font-medium">{{ $lead->website }}</a>
                    @else
                        <span class="text-gray-400">N/A</span>
                    @endif
                </p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Address</label>
                <p class="font-medium">{{ $lead->address ?: '-' }}</p>
            </div>

            <div>
                <label class="text-sm text-gray-500">Area</label>
                <p class="font-medium">{{ $lead->area ?: '-' }}</p>
            </div>

            @if($lead->lat && $lead->lon)
            <div>
                <label class="text-sm text-gray-500">Map</label>
                <p>
                    <a href="https://www.openstreetmap.org/?mlat={{ $lead->lat }}&mlon={{ $lead->lon }}#map=16/{{ $lead->lat }}/{{ $lead->lon }}"
                       target="_blank" class="text-indigo-600 hover:underline">
                        View on OpenStreetMap
                    </a>
                </p>
            </div>
            @endif
        </div>

        <div class="mt-8 flex gap-4">
            <a href="{{ route('leads.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">Back to Leads</a>
            <form action="{{ route('leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

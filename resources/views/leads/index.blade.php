@extends('layouts.app')

@section('title', 'Leads - Lead Finder')

@section('content')

{{-- Search Form --}}
<div class="mx-4 mb-6">
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('leads.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <input type="hidden" name="fetch_osm" value="1">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Area / Location</label>
            <input type="text" name="area" value="{{ $filters['area'] ?? '' }}" placeholder="e.g. Calgary, Alberta"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
            <input type="text" name="type" value="{{ $filters['type'] ?? '' }}" placeholder="e.g. lawyer, restaurant, plumber"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition w-full md:w-auto">
                Search Leads
            </button>
        </div>
    </form>
</div>
</div>

{{-- Filters --}}
<div class="mx-4 mb-6">
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('leads.index') }}" method="GET" id="filterForm">
        @if(!empty($filters['area'])) <input type="hidden" name="area" value="{{ $filters['area'] }}"> @endif
        @if(!empty($filters['type'])) <input type="hidden" name="type" value="{{ $filters['type'] }}"> @endif

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, address, phone..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" id="searchInput">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="filter_category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($filters['filter_category'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Has Phone</label>
                <select name="filter_phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Any</option>
                    <option value="yes" {{ ($filters['filter_phone'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ ($filters['filter_phone'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Has Website</label>
                <select name="filter_website" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Any</option>
                    <option value="yes" {{ ($filters['filter_website'] ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ ($filters['filter_website'] ?? '') === 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min Rating</label>
                <select name="filter_rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Any</option>
                    <option value="4" {{ ($filters['filter_rating'] ?? '') === '4' ? 'selected' : '' }}>4+</option>
                    <option value="3" {{ ($filters['filter_rating'] ?? '') === '3' ? 'selected' : '' }}>3+</option>
                    <option value="2" {{ ($filters['filter_rating'] ?? '') === '2' ? 'selected' : '' }}>2+</option>
                    <option value="1" {{ ($filters['filter_rating'] ?? '') === '1' ? 'selected' : '' }}>1+</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                <input type="text" name="filter_website_url" value="{{ $filters['filter_website_url'] ?? '' }}" placeholder="Search by domain..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" id="websiteUrlInput">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website Quality</label>
                <select name="filter_website_quality" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Good" {{ ($filters['filter_website_quality'] ?? '') === 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Average" {{ ($filters['filter_website_quality'] ?? '') === 'Average' ? 'selected' : '' }}>Average</option>
                    <option value="Bad" {{ ($filters['filter_website_quality'] ?? '') === 'Bad' ? 'selected' : '' }}>Bad</option>
                    <option value="Error" {{ ($filters['filter_website_quality'] ?? '') === 'Error' ? 'selected' : '' }}>Error</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Status</label>
                <select name="filter_contact_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Mail" {{ ($filters['filter_contact_status'] ?? '') === 'Mail' ? 'selected' : '' }}>Mail</option>
                    <option value="WhatsApp" {{ ($filters['filter_contact_status'] ?? '') === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                    <option value="SMS" {{ ($filters['filter_contact_status'] ?? '') === 'SMS' ? 'selected' : '' }}>SMS</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort_by" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="newest" {{ ($filters['sort_by'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ ($filters['sort_by'] ?? '') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ ($filters['sort_by'] ?? '') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ ($filters['sort_by'] ?? '') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="rating_high" {{ ($filters['sort_by'] ?? '') === 'rating_high' ? 'selected' : '' }}>Highest Rating</option>
                    <option value="rating_low" {{ ($filters['sort_by'] ?? '') === 'rating_low' ? 'selected' : '' }}>Lowest Rating</option>
                    <option value="reviews" {{ ($filters['sort_by'] ?? '') === 'reviews' ? 'selected' : '' }}>Most Reviews</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="{{ route('leads.index') }}" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg transition w-full">
                    Clear All
                </a>
            </div>
        </div>
    </form>
</div>
</div>

{{-- Results --}}
<div class="bg-white rounded-lg shadow overflow-hidden mx-4">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Leads ({{ $leads->total() }} total)</h2>
    </div>

    @if($leads->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website Quality</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($leads as $lead)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $lead->company_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm email-cell">
                                <input type="email" value="{{ $lead->email }}" placeholder="Add email..."
                                    class="email-input w-full border border-gray-300 hover:border-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded px-2 py-1 text-sm bg-white transition"
                                    data-id="{{ $lead->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($lead->website)
                                    <a href="{{ $lead->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 truncate block max-w-[200px]">
                                        {{ parse_url($lead->website, PHP_URL_HOST) ?? $lead->website }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-[300px] break-words">{{ $lead->address ?: '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($lead->rating)
                                    <span class="text-yellow-500 font-semibold">★ {{ number_format($lead->rating, 1) }}</span>
                                    <span class="text-gray-400 text-xs">({{ $lead->total_ratings ?? 0 }})</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <select class="border rounded px-2 py-1 text-sm font-medium quality-select" data-id="{{ $lead->id }}" style="background-color: @if(($lead->website_quality ?? '') === 'Good') #d1fae5; color: #065f46; @elseif(($lead->website_quality ?? '') === 'Average') #fef3c7; color: #92400e; @elseif(($lead->website_quality ?? '') === 'Bad') #fee2e2; color: #991b1b; @elseif(($lead->website_quality ?? '') === 'Error') #e5e7eb; color: #374151; @else #f9fafb; color: #6b7280; @endif">
                                    <option value="" style="background-color:#f9fafb; color:#6b7280;">-</option>
                                    <option value="Good" {{ ($lead->website_quality ?? '') === 'Good' ? 'selected' : '' }} style="background-color:#d1fae5; color:#065f46;">Good</option>
                                    <option value="Average" {{ ($lead->website_quality ?? '') === 'Average' ? 'selected' : '' }} style="background-color:#fef3c7; color:#92400e;">Average</option>
                                    <option value="Bad" {{ ($lead->website_quality ?? '') === 'Bad' ? 'selected' : '' }} style="background-color:#fee2e2; color:#991b1b;">Bad</option>
                                    <option value="Error" {{ ($lead->website_quality ?? '') === 'Error' ? 'selected' : '' }} style="background-color:#e5e7eb; color:#374151;">Error</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <select class="border rounded px-2 py-1 text-sm font-medium contact-select" data-id="{{ $lead->id }}" style="background-color: @if(($lead->contact_status ?? '') === 'Mail') #dbeafe; color: #1e40af; @elseif(($lead->contact_status ?? '') === 'WhatsApp') #d1fae5; color: #065f46; @elseif(($lead->contact_status ?? '') === 'SMS') #fef3c7; color: #92400e; @else #f9fafb; color: #6b7280; @endif">
                                    <option value="" style="background-color:#f9fafb; color:#6b7280;">-</option>
                                    <option value="Mail" {{ ($lead->contact_status ?? '') === 'Mail' ? 'selected' : '' }} style="background-color:#dbeafe; color:#1e40af;">Mail</option>
                                    <option value="WhatsApp" {{ ($lead->contact_status ?? '') === 'WhatsApp' ? 'selected' : '' }} style="background-color:#d1fae5; color:#065f46;">WhatsApp</option>
                                    <option value="SMS" {{ ($lead->contact_status ?? '') === 'SMS' ? 'selected' : '' }} style="background-color:#fef3c7; color:#92400e;">SMS</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-sm note-cell">
                                <textarea rows="2"
                                    class="note-input w-full border border-gray-300 hover:border-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded px-2 py-1 text-sm bg-white transition"
                                    data-id="{{ $lead->id }}">{{ $lead->note }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $leads->links() }}
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500 text-lg">No leads found.</p>
            <p class="text-gray-400 text-sm mt-2">Use the search form above to find leads by area and business type.</p>
        </div>
    @endif
</div>

<script>
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        document.getElementById('filterForm').submit();
    }, 500);
});

document.querySelectorAll('.quality-select').forEach(function(select) {
    select.addEventListener('change', function() {
        var leadId = this.dataset.id;
        var quality = this.value;
        fetch('/leads/' + leadId + '/quality', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ website_quality: quality })
        }).then(function(r) { return r.json(); }).then(function(d) { console.log(d); });
    });
});

document.querySelectorAll('.contact-select').forEach(function(select) {
    select.addEventListener('change', function() {
        var leadId = this.dataset.id;
        var status = this.value;
        fetch('/leads/' + leadId + '/contact', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ contact_status: status })
        }).then(function(r) { return r.json(); }).then(function(d) { console.log(d); });
    });
});

let websiteUrlTimeout;
document.getElementById('websiteUrlInput').addEventListener('input', function() {
    clearTimeout(websiteUrlTimeout);
    websiteUrlTimeout = setTimeout(function() {
        document.getElementById('filterForm').submit();
    }, 500);
});

let emailTimeouts = {};
document.querySelectorAll('.email-input').forEach(function(input) {
    input.addEventListener('input', function() {
        var leadId = this.dataset.id;
        var email = this.value;
        clearTimeout(emailTimeouts[leadId]);
        emailTimeouts[leadId] = setTimeout(function() {
            fetch('/leads/' + leadId + '/email', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            }).then(function(r) { return r.json(); }).then(function(d) { console.log(d); });
        }, 600);
    });
});

let noteTimeouts = {};
document.querySelectorAll('.note-input').forEach(function(input) {
    input.addEventListener('input', function() {
        var leadId = this.dataset.id;
        var note = this.value;
        clearTimeout(noteTimeouts[leadId]);
        noteTimeouts[leadId] = setTimeout(function() {
            fetch('/leads/' + leadId + '/note', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ note: note })
            }).then(function(r) { return r.json(); }).then(function(d) { console.log(d); });
        }, 600);
    });
});
</script>

@endsection

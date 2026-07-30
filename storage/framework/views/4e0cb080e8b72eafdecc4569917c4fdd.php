<?php $__env->startSection('title', 'Leads - Lead Finder'); ?>
<?php $__env->startSection('page_title', 'Leads'); ?>

<?php $__env->startSection('content'); ?>


<div class="mx-4 mb-6">
<div class="bg-white rounded-lg shadow p-6">
    <form action="<?php echo e(route('leads.index')); ?>" method="GET" class="flex flex-col md:flex-row gap-4">
        <input type="hidden" name="fetch_osm" value="1">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Area / Location</label>
            <input type="text" name="area" value="<?php echo e($filters['area'] ?? ''); ?>" placeholder="e.g. Calgary, Alberta"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
            <input type="text" name="type" value="<?php echo e($filters['type'] ?? ''); ?>" placeholder="e.g. lawyer, restaurant, plumber"
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


<div class="mx-4 mb-6">
<div class="bg-white rounded-lg shadow p-6">
    <form action="<?php echo e(route('leads.index')); ?>" method="GET" id="filterForm">
        <?php if(!empty($filters['area'])): ?> <input type="hidden" name="area" value="<?php echo e($filters['area']); ?>"> <?php endif; ?>
        <?php if(!empty($filters['type'])): ?> <input type="hidden" name="type" value="<?php echo e($filters['type']); ?>"> <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Name, address, phone..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" id="searchInput">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="filter_category" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(($filters['filter_category'] ?? '') === $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="text" name="filter_email" value="<?php echo e($filters['filter_email'] ?? ''); ?>" placeholder="Filter by email..."
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" id="emailFilterInput">
                            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Has Website</label>
                <select name="filter_website" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Any</option>
                    <option value="yes" <?php echo e(($filters['filter_website'] ?? '') === 'yes' ? 'selected' : ''); ?>>Yes</option>
                    <option value="no" <?php echo e(($filters['filter_website'] ?? '') === 'no' ? 'selected' : ''); ?>>No</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min Rating</label>
                <select name="filter_rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Any</option>
                    <option value="4" <?php echo e(($filters['filter_rating'] ?? '') === '4' ? 'selected' : ''); ?>>4+</option>
                    <option value="3" <?php echo e(($filters['filter_rating'] ?? '') === '3' ? 'selected' : ''); ?>>3+</option>
                    <option value="2" <?php echo e(($filters['filter_rating'] ?? '') === '2' ? 'selected' : ''); ?>>2+</option>
                    <option value="1" <?php echo e(($filters['filter_rating'] ?? '') === '1' ? 'selected' : ''); ?>>1+</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website URL</label>
                <input type="text" name="filter_website_url" value="<?php echo e($filters['filter_website_url'] ?? ''); ?>" placeholder="Search by domain..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" id="websiteUrlInput">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website Quality</label>
                <select name="filter_website_quality" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Good" <?php echo e(($filters['filter_website_quality'] ?? '') === 'Good' ? 'selected' : ''); ?>>Good</option>
                    <option value="Average" <?php echo e(($filters['filter_website_quality'] ?? '') === 'Average' ? 'selected' : ''); ?>>Average</option>
                    <option value="Bad" <?php echo e(($filters['filter_website_quality'] ?? '') === 'Bad' ? 'selected' : ''); ?>>Bad</option>
                    <option value="Error" <?php echo e(($filters['filter_website_quality'] ?? '') === 'Error' ? 'selected' : ''); ?>>Error</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Status</label>
                <select name="filter_contact_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Mail" <?php echo e(($filters['filter_contact_status'] ?? '') === 'Mail' ? 'selected' : ''); ?>>Mail</option>
                    <option value="WhatsApp" <?php echo e(($filters['filter_contact_status'] ?? '') === 'WhatsApp' ? 'selected' : ''); ?>>WhatsApp</option>
                    <option value="SMS" <?php echo e(($filters['filter_contact_status'] ?? '') === 'SMS' ? 'selected' : ''); ?>>SMS</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort_by" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="newest" <?php echo e(($filters['sort_by'] ?? 'newest') === 'newest' ? 'selected' : ''); ?>>Newest First</option>
                    <option value="oldest" <?php echo e(($filters['sort_by'] ?? '') === 'oldest' ? 'selected' : ''); ?>>Oldest First</option>
                    <option value="name_asc" <?php echo e(($filters['sort_by'] ?? '') === 'name_asc' ? 'selected' : ''); ?>>Name A-Z</option>
                    <option value="name_desc" <?php echo e(($filters['sort_by'] ?? '') === 'name_desc' ? 'selected' : ''); ?>>Name Z-A</option>
                    <option value="rating_high" <?php echo e(($filters['sort_by'] ?? '') === 'rating_high' ? 'selected' : ''); ?>>Highest Rating</option>
                    <option value="rating_low" <?php echo e(($filters['sort_by'] ?? '') === 'rating_low' ? 'selected' : ''); ?>>Lowest Rating</option>
                    <option value="reviews" <?php echo e(($filters['sort_by'] ?? '') === 'reviews' ? 'selected' : ''); ?>>Most Reviews</option>
                </select>
            </div>
            <div class="flex items-end">
                <a href="<?php echo e(route('leads.index')); ?>" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg transition w-full">
                    Clear All
                </a>
            </div>
        </div>
    </form>
</div>
</div>


<div class="bg-white rounded-lg shadow overflow-hidden mx-4">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Leads (<?php echo e($leads->total()); ?> total)</h2>
    </div>

    <?php if($leads->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-96">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality / Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 w-96 align-top">
                                <div class="text-sm font-semibold text-gray-900"><?php echo e($lead->company_name); ?></div>
                                <div class="text-xs text-gray-500 mt-1 break-words"><?php echo e($lead->address ?: '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm email-cell">
                                <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                    </svg>
                                    <input type="email" value="<?php echo e($lead->email); ?>" placeholder="Add email..."
                                        class="email-input w-full border border-gray-300 hover:border-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded px-2 py-1 text-sm bg-white transition"
                                        data-id="<?php echo e($lead->id); ?>">
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <input type="text" value="<?php echo e($lead->whatsapp); ?>" placeholder="WhatsApp..."
                                        class="whatsapp-input w-full border border-gray-300 hover:border-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded px-2 py-1 text-sm bg-white transition"
                                        data-id="<?php echo e($lead->id); ?>">
                                </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($lead->website): ?>
                                    <a href="<?php echo e($lead->website); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs break-all">
                                        <?php echo e(parse_url($lead->website, PHP_URL_HOST) ?? $lead->website); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($lead->rating): ?>
                                    <span class="text-yellow-500 font-semibold">★ <?php echo e(number_format($lead->rating, 1)); ?></span>
                                    <span class="text-gray-400 text-xs">(<?php echo e($lead->total_ratings ?? 0); ?>)</span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-col gap-1">
                                <select class="border rounded px-2 py-1 text-sm font-medium quality-select" data-id="<?php echo e($lead->id); ?>" style="background-color: <?php if(($lead->website_quality ?? '') === 'Good'): ?> #d1fae5; color: #065f46; <?php elseif(($lead->website_quality ?? '') === 'Average'): ?> #fef3c7; color: #92400e; <?php elseif(($lead->website_quality ?? '') === 'Bad'): ?> #fee2e2; color: #991b1b; <?php elseif(($lead->website_quality ?? '') === 'Error'): ?> #e5e7eb; color: #374151; <?php else: ?> #f9fafb; color: #6b7280; <?php endif; ?>">
                                    <option value="" style="background-color:#f9fafb; color:#6b7280;">Select Quality</option>
                                    <option value="Good" <?php echo e(($lead->website_quality ?? '') === 'Good' ? 'selected' : ''); ?> style="background-color:#d1fae5; color:#065f46;">Good</option>
                                    <option value="Average" <?php echo e(($lead->website_quality ?? '') === 'Average' ? 'selected' : ''); ?> style="background-color:#fef3c7; color:#92400e;">Average</option>
                                    <option value="Bad" <?php echo e(($lead->website_quality ?? '') === 'Bad' ? 'selected' : ''); ?> style="background-color:#fee2e2; color:#991b1b;">Bad</option>
                                    <option value="Error" <?php echo e(($lead->website_quality ?? '') === 'Error' ? 'selected' : ''); ?> style="background-color:#e5e7eb; color:#374151;">Error</option>
                                </select>
                                <select class="border rounded px-2 py-1 text-sm font-medium w-full contact-select" data-id="<?php echo e($lead->id); ?>" style="background-color: <?php if(($lead->contact_status ?? '') === 'Mail'): ?> #dbeafe; color: #1e40af; <?php elseif(($lead->contact_status ?? '') === 'WhatsApp'): ?> #d1fae5; color: #065f46; <?php elseif(($lead->contact_status ?? '') === 'SMS'): ?> #fef3c7; color: #92400e; <?php else: ?> #f9fafb; color: #6b7280; <?php endif; ?>">
                                    <option value="" style="background-color:#f9fafb; color:#6b7280;">Not Contact Yet</option>
                                    <option value="Mail" <?php echo e(($lead->contact_status ?? '') === 'Mail' ? 'selected' : ''); ?> style="background-color:#dbeafe; color:#1e40af;">Mail</option>
                                    <option value="WhatsApp" <?php echo e(($lead->contact_status ?? '') === 'WhatsApp' ? 'selected' : ''); ?> style="background-color:#d1fae5; color:#065f46;">WhatsApp</option>
                                    <option value="SMS" <?php echo e(($lead->contact_status ?? '') === 'SMS' ? 'selected' : ''); ?> style="background-color:#fef3c7; color:#92400e;">SMS</option>
                                </select>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm note-cell">
                                <textarea rows="2"
                                    class="note-input w-full border border-gray-300 hover:border-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded px-2 py-1 text-sm bg-white transition"
                                    data-id="<?php echo e($lead->id); ?>"><?php echo e($lead->note); ?></textarea>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($leads->links()); ?>

        </div>
    <?php else: ?>
        <div class="px-6 py-12 text-center">
            <p class="text-gray-500 text-lg">No leads found.</p>
            <p class="text-gray-400 text-sm mt-2">Use the search form above to find leads by area and business type.</p>
        </div>
    <?php endif; ?>
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
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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

let emailFilterTimeout;
document.getElementById('emailFilterInput').addEventListener('input', function() {
    clearTimeout(emailFilterTimeout);
    emailFilterTimeout = setTimeout(function() {
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
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ email: email })
            }).then(function(r) { return r.json(); }).then(function(d) { console.log(d); });
        }, 600);
    });
});

let whatsappTimeouts = {};
document.querySelectorAll('.whatsapp-input').forEach(function(input) {
    input.addEventListener('input', function() {
        var leadId = this.dataset.id;
        var whatsapp = this.value;
        clearTimeout(whatsappTimeouts[leadId]);
        whatsappTimeouts[leadId] = setTimeout(function() {
            fetch('/leads/' + leadId + '/whatsapp', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ whatsapp: whatsapp })
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
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ note: note })
            }).then(function(r) { return r.json(); }).then(function(d) { console.log(d); });
        }, 600);
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Lead data\lead-finder\resources\views/leads/index.blade.php ENDPATH**/ ?>
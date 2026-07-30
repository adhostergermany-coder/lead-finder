<?php $__env->startSection('title', 'Leads - Lead Finder'); ?>

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

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Has Phone</label>
                <select name="filter_phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Any</option>
                    <option value="yes" <?php echo e(($filters['filter_phone'] ?? '') === 'yes' ? 'selected' : ''); ?>>Yes</option>
                    <option value="no" <?php echo e(($filters['filter_phone'] ?? '') === 'no' ? 'selected' : ''); ?>>No</option>
                </select>
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
        </div>

        <div class="flex flex-col md:flex-row gap-4 mt-4 items-end">
            <div class="flex-1">
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
            <a href="<?php echo e(route('leads.index')); ?>" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg transition">
                Clear All
            </a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website Quality</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($lead->id); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900"><?php echo e($lead->company_name); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($lead->phone): ?>
                                    <a href="tel:<?php echo e($lead->phone); ?>" class="text-indigo-600 hover:text-indigo-800"><?php echo e($lead->phone); ?></a>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($lead->email): ?>
                                    <a href="mailto:<?php echo e($lead->email); ?>" class="text-indigo-600 hover:text-indigo-800 truncate block max-w-[200px]"><?php echo e($lead->email); ?></a>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($lead->website): ?>
                                    <a href="<?php echo e($lead->website); ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 truncate block max-w-[200px]">
                                        <?php echo e(parse_url($lead->website, PHP_URL_HOST) ?? $lead->website); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-[300px] break-words"><?php echo e($lead->address ?: '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php if($lead->rating): ?>
                                    <span class="text-yellow-500 font-semibold">★ <?php echo e(number_format($lead->rating, 1)); ?></span>
                                    <span class="text-gray-400 text-xs">(<?php echo e($lead->total_ratings ?? 0); ?>)</span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <select class="border rounded px-2 py-1 text-sm font-medium quality-select" data-id="<?php echo e($lead->id); ?>" style="background-color: <?php if(($lead->website_quality ?? '') === 'Good'): ?> #d1fae5; color: #065f46; <?php elseif(($lead->website_quality ?? '') === 'Average'): ?> #fef3c7; color: #92400e; <?php elseif(($lead->website_quality ?? '') === 'Bad'): ?> #fee2e2; color: #991b1b; <?php elseif(($lead->website_quality ?? '') === 'Error'): ?> #e5e7eb; color: #374151; <?php else: ?> #f9fafb; color: #6b7280; <?php endif; ?>">
                                    <option value="" style="background-color:#f9fafb; color:#6b7280;">-</option>
                                    <option value="Good" <?php echo e(($lead->website_quality ?? '') === 'Good' ? 'selected' : ''); ?> style="background-color:#d1fae5; color:#065f46;">Good</option>
                                    <option value="Average" <?php echo e(($lead->website_quality ?? '') === 'Average' ? 'selected' : ''); ?> style="background-color:#fef3c7; color:#92400e;">Average</option>
                                    <option value="Bad" <?php echo e(($lead->website_quality ?? '') === 'Bad' ? 'selected' : ''); ?> style="background-color:#fee2e2; color:#991b1b;">Bad</option>
                                    <option value="Error" <?php echo e(($lead->website_quality ?? '') === 'Error' ? 'selected' : ''); ?> style="background-color:#e5e7eb; color:#374151;">Error</option>
                                </select>
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
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Lead data\lead-finder\resources\views/leads/index.blade.php ENDPATH**/ ?>
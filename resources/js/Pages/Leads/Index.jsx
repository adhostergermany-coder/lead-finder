import { useState } from 'react';
import { router, Link } from '@inertiajs/react';
import Layout from '@/Components/Layout';

export default function Index({ leads, categories, filters, apiKey, flash }) {
    const [search, setSearch] = useState(filters.search || '');
    const [filterCategory, setFilterCategory] = useState(filters.filter_category || '');
    const [filterPhone, setFilterPhone] = useState(filters.filter_phone || '');
    const [filterWebsite, setFilterWebsite] = useState(filters.filter_website || '');
    const [filterRating, setFilterRating] = useState(filters.filter_rating || '');
    const [sortBy, setSortBy] = useState(filters.sort_by || 'newest');

    const [area, setArea] = useState(filters.area || '');
    const [type, setType] = useState(filters.type || '');
    const [loading, setLoading] = useState(false);

    const handleSearch = (e) => {
        e.preventDefault();
        setLoading(true);
        router.get('/', { fetch_osm: 1, area, type }, { onFinish: () => setLoading(false) });
    };

    const handleFilter = (e) => {
        e.preventDefault();
        router.get('/', {
            area: filters.area,
            type: filters.type,
            search,
            filter_category: filterCategory,
            filter_phone: filterPhone,
            filter_website: filterWebsite,
            filter_rating: filterRating,
            sort_by: sortBy,
        }, { preserveState: true });
    };

    const clearFilters = () => {
        setSearch('');
        setFilterCategory('');
        setFilterPhone('');
        setFilterWebsite('');
        setFilterRating('');
        setSortBy('newest');
        router.get('/', { area: filters.area, type: filters.type }, { preserveState: true });
    };

    const deleteLead = (id) => {
        if (confirm('Delete this lead?')) {
            router.delete(`/leads/${id}`);
        }
    };

    return (
        <Layout>
            <div className="space-y-6">
                {/* Search Form */}
                <div className="bg-indigo-600 text-white rounded-lg shadow-md p-6">
                    <h1 className="text-2xl font-bold mb-2">Search Business Leads</h1>
                    <p className="text-indigo-200 text-sm mb-4">
                        {apiKey
                            ? 'Using Google Places API (full contact info)'
                            : 'Using free OpenStreetMap (limited contact info)'}
                    </p>

                    <form onSubmit={handleSearch} className="flex gap-4 items-end">
                        <div className="flex-1">
                            <label className="block text-sm mb-1">Area</label>
                            <input type="text" required placeholder="e.g. Calgary, Alberta, Canada"
                                value={area} onChange={(e) => setArea(e.target.value)}
                                className="w-full px-4 py-2 rounded-lg text-gray-800 focus:ring-2 focus:ring-indigo-300" />
                        </div>
                        <div className="flex-1">
                            <label className="block text-sm mb-1">Business Type</label>
                            <input type="text" required placeholder="e.g. lawyer, restaurant, pharmacy"
                                value={type} onChange={(e) => setType(e.target.value)}
                                className="w-full px-4 py-2 rounded-lg text-gray-800 focus:ring-2 focus:ring-indigo-300" />
                        </div>
                        <button type="submit" disabled={loading}
                            className="bg-white text-indigo-600 px-6 py-2 rounded-lg font-semibold hover:bg-indigo-50 transition whitespace-nowrap disabled:opacity-50">
                            {loading ? 'Searching...' : 'Search New Leads'}
                        </button>
                    </form>
                </div>

                {/* Flash Messages */}
                {flash?.success && (
                    <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {flash.success}
                    </div>
                )}
                {flash?.error && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {flash.error}
                    </div>
                )}

                {/* Advanced Filters */}
                {leads.data.length > 0 && (
                    <div className="bg-white rounded-lg shadow-md p-4">
                        <form onSubmit={handleFilter} className="space-y-4">
                            <div className="flex items-center justify-between">
                                <h3 className="font-semibold text-gray-700">Advanced Filters</h3>
                                <button type="button" onClick={clearFilters}
                                    className="text-sm text-red-500 hover:underline">Clear All Filters</button>
                            </div>

                            <div className="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div>
                                    <label className="block text-xs text-gray-500 mb-1">Search Name/Address</label>
                                    <input type="text" placeholder="Search..." value={search}
                                        onChange={(e) => setSearch(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <label className="block text-xs text-gray-500 mb-1">Category</label>
                                    <select value={filterCategory} onChange={(e) => setFilterCategory(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="">All Categories</option>
                                        {categories.map((cat) => (
                                            <option key={cat} value={cat}>{cat}</option>
                                        ))}
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-xs text-gray-500 mb-1">Phone</label>
                                    <select value={filterPhone} onChange={(e) => setFilterPhone(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="">All</option>
                                        <option value="yes">Has Phone</option>
                                        <option value="no">No Phone</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-xs text-gray-500 mb-1">Website</label>
                                    <select value={filterWebsite} onChange={(e) => setFilterWebsite(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="">All</option>
                                        <option value="yes">Has Website</option>
                                        <option value="no">No Website</option>
                                    </select>
                                </div>
                                <div>
                                    <label className="block text-xs text-gray-500 mb-1">Min Rating</label>
                                    <select value={filterRating} onChange={(e) => setFilterRating(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Any Rating</option>
                                        <option value="4.5">4.5+</option>
                                        <option value="4">4.0+</option>
                                        <option value="3.5">3.5+</option>
                                        <option value="3">3.0+</option>
                                    </select>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label className="block text-xs text-gray-500 mb-1">Sort By</label>
                                    <select value={sortBy} onChange={(e) => setSortBy(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                                        <option value="newest">Newest First</option>
                                        <option value="oldest">Oldest First</option>
                                        <option value="name_asc">Name A-Z</option>
                                        <option value="name_desc">Name Z-A</option>
                                        <option value="rating_high">Highest Rating</option>
                                        <option value="rating_low">Lowest Rating</option>
                                        <option value="reviews">Most Reviews</option>
                                    </select>
                                </div>
                                <div className="flex items-end">
                                    <button type="submit"
                                        className="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 transition">
                                        Apply Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                )}

                {/* Results Table */}
                {leads.data.length > 0 ? (
                    <div className="bg-white rounded-lg shadow-md overflow-hidden">
                        <div className="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h2 className="text-lg font-semibold text-gray-800">Leads ({leads.total})</h2>
                            {filters.area && (
                                <span className="text-sm text-gray-500">Area: {filters.area} | Type: {filters.type}</span>
                            )}
                        </div>
                        <table className="w-full">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Website</th>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                    <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-200">
                                {leads.data.map((lead) => (
                                    <tr key={lead.id} className="hover:bg-gray-50">
                                        <td className="px-4 py-4">
                                            <div className="font-medium text-gray-900">{lead.company_name}</div>
                                            <div className="text-sm text-gray-500">{lead.category}</div>
                                        </td>
                                        <td className="px-4 py-4">
                                            {lead.phone ? (
                                                <a href={`tel:${lead.phone}`} className="text-green-600 hover:underline font-medium">{lead.phone}</a>
                                            ) : (
                                                <span className="text-gray-400">N/A</span>
                                            )}
                                        </td>
                                        <td className="px-4 py-4">
                                            {lead.email ? (
                                                <a href={`mailto:${lead.email}`} className="text-blue-600 hover:underline">{lead.email}</a>
                                            ) : (
                                                <span className="text-gray-400">N/A</span>
                                            )}
                                        </td>
                                        <td className="px-4 py-4">
                                            {lead.website ? (
                                                <a href={lead.website} target="_blank" rel="noopener noreferrer" className="text-indigo-600 hover:underline text-sm">Visit</a>
                                            ) : (
                                                <span className="text-gray-400">N/A</span>
                                            )}
                                        </td>
                                        <td className="px-4 py-4 text-sm text-gray-500 max-w-xs truncate">{lead.address || lead.area}</td>
                                        <td className="px-4 py-4 text-sm">
                                            {lead.rating ? (
                                                <span>
                                                    <span className="text-yellow-500 font-medium">{lead.rating}</span>
                                                    <span className="text-gray-400"> ({lead.total_ratings})</span>
                                                </span>
                                            ) : (
                                                <span className="text-gray-400">N/A</span>
                                            )}
                                        </td>
                                        <td className="px-4 py-4">
                                            <button onClick={() => deleteLead(lead.id)}
                                                className="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                        {/* Pagination */}
                        {leads.last_page > 1 && (
                            <div className="px-6 py-4 border-t border-gray-200 flex justify-center gap-2">
                                {leads.links.map((link, i) => (
                                    <Link key={i} href={link.url || '#'}
                                        className={`px-3 py-1 rounded text-sm ${link.active ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border'}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }} />
                                ))}
                            </div>
                        )}
                    </div>
                ) : (
                    <div className="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                        <p className="text-lg mb-2">Enter area and business type above to search</p>
                    </div>
                )}
            </div>
        </Layout>
    );
}

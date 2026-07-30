<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Lead Finder')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside class="w-64 bg-indigo-800 text-white flex-shrink-0">
            <div class="p-4 border-b border-indigo-700">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight">Lead Finder</a>
                <p class="text-xs text-indigo-300 mt-1">Admin Panel</p>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('leads.index') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('leads.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    Leads
                </a>

                <a href="{{ route('admin.users.create') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Create User
                </a>

                <form method="POST" action="{{ route('logout') }}" class="pt-4 border-t border-indigo-700">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-4 py-2.5 w-full text-left rounded-lg text-indigo-200 hover:bg-indigo-700 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">@yield('page_title', 'Admin')</h2>
                    <div class="text-sm text-gray-500">
                        {{ Auth::user()->name ?? Auth::user()->email }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto py-6">
                @if(session('success'))
                    <div class="mx-4 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-green-700 font-bold ml-4">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mx-4 mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-red-700 font-bold ml-4">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Lead Finder')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-indigo-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-tight">Lead Finder</a>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('dashboard') }}"
                            class="px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('leads.index') }}"
                            class="px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('leads.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                            Leads
                        </a>
                        <a href="{{ route('admin.users.create') }}"
                            class="px-3 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-700 hover:text-white' }}">
                            Create User
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-indigo-200">{{ Auth::user()->name ?? Auth::user()->email }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-indigo-200 hover:text-white transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        @if(session('success'))
            <div class="max-w-7xl mx-auto mb-4 px-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-700 font-bold ml-4">&times;</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto mb-4 px-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700 font-bold ml-4">&times;</button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>

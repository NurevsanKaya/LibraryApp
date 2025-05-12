<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('anasayfa') }}" class="text-xl font-bold text-gray-800">
                            Kütüphane
                        </a>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = ! open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Çıkış Yap
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 h-screen bg-white border-r shadow-md px-4 py-6 mt-2">
            <a href="{{ route('dashboard') }}" class="block">
                <h1 class="text-2xl font-bold text-gray-800 mb-8 text-center hover:text-indigo-600 transition-colors">📚 Kullanıcı Paneli</h1>
            </a>

            <nav class="space-y-3">
                <a href="{{ route('user.borrowings') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all
                  {{ request()->routeIs('user.borrowings') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fas fa-book-reader"></i>
                    <span>Aktif Ödünç İşlemleri</span>
                </a>
                <a href="{{ route('user.oldborrowings') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all
                  {{ request()->routeIs('user.oldborrowings') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fas fa-history"></i>
                    <span>Geçmiş Ödünç İşlemleri</span>
                </a>

                <a href="{{ route('user.overdue') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all
                  {{ request()->routeIs('user.overdue') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Geçikmiş Kitaplarım</span>
                </a>

                <a href="{{ route('user.penalties') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all
                  {{ request()->routeIs('user.penalties') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Ceza / Borç Bilgisi</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>
</body>
</html>

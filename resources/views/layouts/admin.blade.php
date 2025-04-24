<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>KÜTÜPHANE YÖNETİM PANELİ</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Yazarlar kısmı için select2 css -->
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 42px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 5px;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Admin Navbar -->
        <nav class="bg-white border-b border-gray-100 shadow">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
                                Kütüphane Yönetim Paneli
                            </a>
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                            <div @click="open = ! open">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 style="display: none;"
                                 @click="open = false">
                                <!-- Profile Link -->
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Profil
                                </a>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Çıkış Yap
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            <div class="w-64 bg-white shadow h-screen">
                <div class="py-4 px-6">
                    <ul class="mt-6">
                        <li class="mb-3">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-tachometer-alt mr-3"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('admin.books.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.books.*') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-book mr-3"></i>
                                Kitap Yönetimi
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('admin.stocks.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.stocks.*') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-boxes mr-3"></i>
                                Stok Yönetimi
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('admin.data.adding') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.data.adding') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-plus-circle mr-3"></i>
                                Veri Ekleme
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-users mr-3"></i>
                                Kullanıcı Yönetimi
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('admin.borrowings.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.borrowings.*') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-exchange-alt mr-3"></i>
                                Kitap Ödünç İşlemleri
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('admin.payments.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md {{ request()->routeIs('admin.borrowings.*') ? 'bg-gray-200' : '' }}">
                                <i class="fas fa-exchange-alt mr-3"></i>
                              Ceza İşlemleri
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 p-8">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>

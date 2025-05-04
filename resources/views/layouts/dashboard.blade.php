<x-app-layout>
    {{-- Header kısmını kaldırıyoruz --}}

    <div class="flex min-h-screen bg-gray-100 "> {{-- pt-8 ile her şeyi yukarı alıyoruz --}}

        <!-- Şık Sidebar -->
        <aside class="w-64 h-screen bg-white border-r shadow-md px-4 py-6 mt-2">
            <h1 class="text-2xl font-bold text-gray-800 mb-8 text-center">📚 Kullanıcı Paneli</h1>

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
                    <i class="fas fa-book-reader"></i>
                    <span>Geçmiş Ödünç İşlemleri</span>
                </a>

                <a href="{{ route('user.penalties') }}"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all
                  {{ request()->routeIs('user.penalties') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Ceza / Borç Bilgisi</span>
                </a>

            </nav>
        </aside>


        <!-- İçerik Alanı -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>

    </div>
</x-app-layout>

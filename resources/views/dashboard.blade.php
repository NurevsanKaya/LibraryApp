@extends('layouts.dashboard')
@section('title', 'My Borrowings')
@section('content')
        <!-- Ana İçerik -->
        <main class="flex-1 flex justify-center pt-2 px-8"> {{-- pt-2: biraz daha yukarıda dursun --}}
            <div class="w-full max-w-5xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Hoş Geldin, {{ auth()->user()->name }} 👋</h2>

                <!-- Gecikmiş Kitaplar Uyarısı (varsa) -->
                @if(isset($overdueBooks) && $overdueBooks->count() > 0)
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow">
                        <div class="flex items-center">
                            <div class="py-1">
                                <svg class="h-6 w-6 text-red-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">Dikkat! Gecikmiş Kitaplarınız Bulunuyor</p>
                                <p class="text-sm mt-1">{{ $overdueBooks->count() }} adet kitabın iade tarihi geçmiştir. Lütfen en kısa sürede kütüphaneye iade ediniz.</p>
                            </div>
                        </div>
                        
                        <ul class="mt-3 text-sm list-disc list-inside">
                            @foreach($overdueBooks as $book)
                                <li>
                                    <span class="font-medium">{{ $book->stock->book->name ?? 'Bilinmeyen Kitap' }}</span> - 
                                    <span class="text-red-600">Son İade Tarihi: {{ \Carbon\Carbon::parse($book->due_date)->format('d.m.Y') }}</span>
                                    ({{ floor(\Carbon\Carbon::parse($book->due_date)->diffInDays(now())) }} gün gecikme)
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ödünç Alınan Kitaplar -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-2">📚 Ödünç Alınan Kitaplar</h3>

                        @if($borrowings->isEmpty())
                            <p class="text-gray-600">Henüz kitap almadınız.</p>
                        @else
                            <p class="text-gray-700">
                                Şu anda {{ $borrowings->count() }} kitap ödünç aldınız. 📖
                            </p>
                            <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                                @foreach($borrowings as $b)
                                    <li>{{ $b->stock->book->name ?? '-' }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Ceza / Borç Bilgisi -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-2">💸 Ceza / Borç Bilgisi</h3>

                        @if($penalties->isEmpty())
                            <p class="text-gray-600">Cezanız bulunmamaktadır. Devam! 😎</p>
                        @else
                            <p class="text-gray-700">
                                {{ $penalties->where('status', '!=', 'onaylandı')->count() }} adet ödemeniz gereken cezanız var. 😬
                            </p>
                            <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                                @foreach($penalties->where('status', '!=', 'onaylandı') as $p)
                                    <li>{{ $p->amount }} ₺ - {{ $p->payment_method }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

            </div>

        </main>

@endsection

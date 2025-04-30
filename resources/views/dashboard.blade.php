@extends('layouts.dashboard')
@section('title', 'My Borrowings')
@section('content')
        <!-- Ana İçerik -->
        <main class="flex-1 flex justify-center pt-2 px-8"> {{-- pt-2: biraz daha yukarıda dursun --}}
            <div class="w-full max-w-5xl">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Hoş Geldin, {{ auth()->user()->name }} 👋</h2>

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
                                {{ $penalties->where('status', '!=', 'ödendi')->count() }} adet ödemeniz gereken cezanız var. 😬
                            </p>
                            <ul class="mt-2 text-sm text-gray-600 list-disc list-inside">
                                @foreach($penalties->where('status', '!=', 'ödendi') as $p)
                                    <li>{{ $p->amount }} ₺ - {{ $p->payment_method }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

            </div>

        </main>

@endsection

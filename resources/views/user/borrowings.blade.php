@extends('layouts.dashboard')
@section('title', 'My Borrowings')
@section('content')
    <div class="max-w-6xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6">📚 Ödünç Aldıklarım</h2>

        @if($borrowings->isEmpty())
            <p class="text-gray-600">Henüz hiç kitap ödünç almadınız.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($borrowings as $item)
                    <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                        <h3 class="text-lg font-semibold mb-1 text-indigo-700">{{ $item->stock->book->name }}</h3>

                        <p class="text-sm text-gray-600">
                            <strong>Yazar:</strong>
                            @foreach($item->stock->book->authors as $author)
                                {{ $author->first_name }} {{ $author->last_name }}@if (!$loop->last), @endif
                            @endforeach
                            <br>
                            <strong>Kategori:</strong> {{ $item->stock->book->category->name ?? 'N/A' }}<br>
                            <strong>Ödünç Tarihi:</strong> {{ $item->borrow_date->format('d.m.Y') }}<br>
                            <strong>İade Tarihi:</strong>
                            <span class="text-red-600">{{ $item->due_date->format('d.m.Y') }}</span><br>

                            @if($item->extended_return_date)
                                <strong>Durum:</strong> <span class="text-indigo-600">Uzatıldı</span><br>
                                <strong>Uzatılmış Tarih:</strong> <span class="text-green-600">{{ $item->extended_return_date->format('d.m.Y') }}</span><br>
                            @endif

                            @if($item->return_date)
                                <strong>Durum:</strong> <span class="text-green-700 font-semibold">İade Edildi</span><br>
                                <strong>Gerçek İade Tarihi:</strong> {{ $item->return_date->format('d.m.Y') }}<br>
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
    @endif
@endsection

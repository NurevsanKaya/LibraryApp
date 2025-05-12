@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Arama Kutusu -->
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="max-w-2xl mx-auto">
            <div class="search-container">
                <input type="text" name="query" value="{{ $query }}"
                    class="search-input"
                    placeholder="Aramak istediğiniz kelimeyi yazın..." required>
                <button type="submit" class="search-button">Ara</button>
            </div>
        </form>
    </div>

    <!-- Arama Sonuçları -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">
            "{{ $query }}" için arama sonuçları
        </h2>

        @if($books->isNotEmpty())
            <div class="bg-white rounded-lg shadow">
                @foreach($books as $book)
                    <div class="p-4 border-b last:border-b-0">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold">{{ $book->name }}</h3>
                                <div class="mt-2 space-y-1">
                                    <p class="text-gray-600">
                                        <span class="font-medium">Yazar:</span>
                                        {{ $book->authors->map(function($author) { return $author->fullName(); })->join(', ') }}
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Yayınevi:</span>
                                        {{ $book->publisher ? $book->publisher->name : 'Yayınevi bilgisi yok' }}
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Kategori:</span>
                                        {{ $book->category ? $book->category->name : 'Kategori bilgisi yok' }}
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">ISBN:</span>
                                        {{ $book->isbn ?? 'ISBN bilgisi yok' }}
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Basım Yılı:</span>
                                        {{ $book->publication_year ?? 'Basım yılı bilgisi yok' }}
                                    </p>
                                    <p class="text-gray-600">
                                        <span class="font-medium">Stok Durumu:</span>
                                        @php
                                            $availableStocks = $book->stocks->where('status', 'available')->count();
                                        @endphp
                                        @if($availableStocks > 0)
                                            <span class="text-green-600 font-medium">{{ $availableStocks }} adet mevcut</span>
                                        @else
                                            <span class="text-red-600 font-medium">Stokta yok</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 flex flex-col items-end">
                                <a href="{{ route('books.show', $book->id) }}"
                                   class="detail-button">
                                    Detay
                                </a>
                                @if($availableStocks > 0)
                                    <span class="mt-2 text-sm text-green-600">
                                        <i class="fas fa-check-circle"></i> Ödünç alınabilir
                                    </span>
                                @else
                                    <span class="mt-2 text-sm text-red-600">
                                        <i class="fas fa-times-circle"></i> Ödünç alınamaz
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sayfalama -->
            <div class="mt-4">
                {{ $books->links() }}
            </div>
        @else
            <div class="text-center text-gray-600 py-8">
                "{{ $query }}" ile ilgili sonuç bulunamadı.
            </div>

            <!-- Öneriler -->
            @if($suggestions && $suggestions->isNotEmpty())
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3">Şunu mu demek istediniz?</h3>
                    <div class="space-y-2">
                        @foreach($suggestions as $suggestion)
                            <a href="{{ route('search', ['query' => $suggestion->name]) }}"
                               class="block text-blue-600 hover:text-blue-800">
                                {{ $suggestion->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<style>
    .search-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 5px 10px;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px;
        font-size: 16px;
        border-radius: 20px;
        background: transparent;
    }

    .search-input::placeholder {
        color: #aaa;
    }

    .search-button {
        background-color: #e63946;
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-button:hover {
        background-color: #d62839;
    }

    .detail-button {
        background-color: #e63946;
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }

    .detail-button:hover {
        background-color: #d62839;
        color: #fff;
    }
</style>
@endsection

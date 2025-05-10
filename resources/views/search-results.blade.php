@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Arama Kutusu -->
    <div class="mb-8">
        <form action="{{ route('search') }}" method="GET" class="max-w-2xl mx-auto">
            <div class="flex gap-2">
                <input type="text" name="query" value="{{ $query }}" 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Kitap adı veya yazar ara...">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                    Ara
                </button>
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
                            <div>
                                <h3 class="text-lg font-semibold">{{ $book->name }}</h3>
                                <p class="text-gray-600">
                                    {{ $book->authors->map(function($author) { return $author->fullName(); })->join(', ') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $book->publisher ? $book->publisher->name : 'Yayınevi bilgisi yok' }}
                                </p>
                            </div>
                            <a href="{{ route('books.show', $book->id) }}" 
                               class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200">
                                Detay
                            </a>
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
@endsection

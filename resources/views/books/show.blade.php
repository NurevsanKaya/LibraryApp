@extends('layouts.app')

@section('content')
@if(isset($book))
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Geri Dön Butonu -->
        <a href="{{ url()->previous() }}" class="inline-block mb-6 text-blue-600 hover:text-blue-800">
            ← Geri Dön
        </a>

        <!-- Kitap Detayları -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-4">{{ $book->name ?? 'İsimsiz Kitap' }}</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sol Taraf: Temel Bilgiler -->
                    <div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold mb-2">Yazarlar</h2>
                            <p class="text-gray-700">
                                @if(isset($book->authors) && $book->authors->isNotEmpty())
                                    {{ $book->authors->map(function($author) { 
                                        return isset($author) ? $author->fullName() : 'Bilinmeyen Yazar'; 
                                    })->join(', ') }}
                                @else
                                    Yazar bilgisi yok
                                @endif
                            </p>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold mb-2">Yayınevi</h2>
                            <p class="text-gray-700">
                                {{ isset($book->publisher) && $book->publisher ? $book->publisher->name : 'Yayınevi bilgisi yok' }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold mb-2">Yayın Yılı</h2>
                            <p class="text-gray-700">{{ $book->publication_year ?? 'Belirtilmemiş' }}</p>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold mb-2">ISBN</h2>
                            <p class="text-gray-700">{{ $book->isbn ?? 'Belirtilmemiş' }}</p>
                        </div>
                    </div>

                    <!-- Sağ Taraf: Kategori ve Stok Bilgileri -->
                    <div>
                        <div class="mb-4">
                            <h2 class="text-lg font-semibold mb-2">Kategori</h2>
                            <p class="text-gray-700">
                                {{ isset($book->category) && $book->category ? $book->category->name : 'Kategori bilgisi yok' }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <h2 class="text-lg font-semibold mb-2">Stok Durumu</h2>
                            <p class="text-gray-700">
                                @if(isset($book->stocks) && $book->stocks->isNotEmpty())
                                    {{ $book->stocks->where('status', 'available')->count() }} adet mevcut
                                @else
                                    Stok bilgisi yok
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="container mx-auto px-4 py-8">
    <div class="text-center text-red-600">
        Kitap bulunamadı.
    </div>
</div>
@endif
@endsection 
@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Metadata Yönetimi</h1>

        <!-- Başarı mesajı -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Kapat</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Yayınevi Formu -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Yayınevi Ekle</h2>
                <form action="{{ route('admin.publishers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="publisher_name" class="block text-sm font-medium text-gray-700 mb-1">Yayınevi Adı</label>
                        <input type="text" name="name" id="publisher_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                        Ekle
                    </button>
                </form>

                <!-- Yayınevi Listesi -->
                <div class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Mevcut Yayınevleri</h3>
                    <div class="max-h-40 overflow-y-auto">
                        <ul class="divide-y divide-gray-200">
                            @forelse($publishers as $publisher)
                                <li class="py-2">{{ $publisher->name }}</li>
                            @empty
                                <li class="py-2 text-gray-500">Henüz yayınevi eklenmemiş.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Kategori Formu -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Kategori Ekle</h2>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="category_name" class="block text-sm font-medium text-gray-700 mb-1">Kategori Adı</label>
                        <input type="text" name="name" id="category_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                        Ekle
                    </button>
                </form>

                <!-- Kategori Listesi -->
                <div class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Mevcut Kategoriler</h3>
                    <div class="max-h-40 overflow-y-auto">
                        <ul class="divide-y divide-gray-200">
                            @forelse($categories as $category)
                                <li class="py-2">{{ $category->name }}</li>
                            @empty
                                <li class="py-2 text-gray-500">Henüz kategori eklenmemiş.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tür Formu -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Tür Ekle</h2>
                <form action="{{ route('admin.genres.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="genre_name" class="block text-sm font-medium text-gray-700 mb-1">Tür Adı</label>
                        <input type="text" name="name" id="genre_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                        Ekle
                    </button>
                </form>

                <!-- Tür Listesi -->
                <div class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Mevcut Türler</h3>
                    <div class="max-h-40 overflow-y-auto">
                        <ul class="divide-y divide-gray-200">
                            @forelse($genres as $genre)
                                <li class="py-2">{{ $genre->name }}</li>
                            @empty
                                <li class="py-2 text-gray-500">Henüz tür eklenmemiş.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Yazar Formu -->
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Yazar Ekle</h2>
                <form action="{{ route('admin.authors.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="author_name" class="block text-sm font-medium text-gray-700 mb-1">Yazar Adı</label>
                        <input type="text" name="first_name" id="author_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="author_surname" class="block text-sm font-medium text-gray-700 mb-1">Yazar Soyadı</label>
                        <input type="text" name="last_name" id="author_surname"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                        Ekle
                    </button>
                </form>

                <!-- Yazar Listesi -->
                <div class="mt-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Mevcut Yazarlar</h3>
                    <div class="max-h-40 overflow-y-auto">
                        <ul class="divide-y divide-gray-200">
                            @forelse($authors as $author)
                                <li class="py-2">{{ $author->first_name }} {{ $author->last_name }}</li>
                            @empty
                                <li class="py-2 text-gray-500">Henüz yazar eklenmemiş.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <!-- Edinme Kaynağı Formu -->
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Edinme Kaynağı Ekle</h2>
                    <form action="{{ route('admin.acquisition-sources.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="source_name" class="block text-sm font-medium text-gray-700 mb-1">Kaynak Adı</label>
                            <input type="text" name="name" id="source_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                            Ekle
                        </button>
                    </form>

                    <!-- Edinme Kaynağı Listesi -->
                    <div class="mt-4">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Mevcut Edinme Kaynakları</h2>
                        <ul class="divide-y divide-gray-200">
                            @forelse($acquisition_source as $source)
                                <li class="py-2">{{ $source->name }}</li>
                            @empty
                                <li class="py-2 text-gray-500">Henüz edinme kaynağı eklenmemiş.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

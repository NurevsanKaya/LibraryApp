@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Kitap Yönetimi</h1>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus mr-2"></i> Yeni Kitap Ekle
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Kitap ara..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-4">
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Kategoriler</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md">
                    <i class="fas fa-filter mr-2"></i> Filtrele
                </button>
            </div>
        </div>

        <!-- Books Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İsim</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ISBN</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Yazar</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Durum</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İçerik Yönetimi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->id }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->isbn }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @if($book->authors->count() > 0)
                                    {{ $book->authors->pluck('name')->join(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->category->name ?? '-' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Mevcut</span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-4 border-b border-gray-200 text-center text-gray-500">
                                Kitap bulunamadı
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </div>
@endsection

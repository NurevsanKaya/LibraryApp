@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Books Card -->
            <div class="bg-blue-100 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-200 mr-4">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Kitap sayısı</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $totalBooks }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Users Card -->
            <div class="bg-green-100 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-200 mr-4">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-green-600 font-medium">Kullanıcı sayısı</p>
                        <p class="text-2xl font-bold text-green-800">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Borrowings Card -->
            <div class="bg-yellow-100 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-200 mr-4">
                        <i class="fas fa-exchange-alt text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-600 font-medium">Aktif Ödünç Alınanlar</p>
                        <p class="text-2xl font-bold text-yellow-800">{{ $activeBorrowings }}</p>
                        <!-- geri getirilme tarihi girilmemiş satırları getirir -->
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-purple-100 rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-200 mr-4">
                        <i class="fas fa-list text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-purple-600 font-medium">Kategoriler</p>
                        <p class="text-2xl font-bold text-purple-800">{{ $totalCategories }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Borrowings -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">En son ödünç alınanlar</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kullanıcı</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kitap</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ödünç tarihi</th>
                                <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Geri Getirme Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBorrowings as $borrowing)
                                <tr>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $borrowing->user->name ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $borrowing->book->name ?? 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $borrowing->borrow_date ? date('d/m/Y', strtotime($borrowing->borrow_date)) : 'N/A' }}</td>
                                    <td class="py-2 px-4 border-b border-gray-200">{{ $borrowing->due_date ? date('d/m/Y', strtotime($borrowing->due_date)) : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-2 px-4 border-b border-gray-200 text-center text-gray-500">Ödünç alınan kitap yok </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

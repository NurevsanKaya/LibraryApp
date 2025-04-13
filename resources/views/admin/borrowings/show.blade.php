@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Ödünç İşlemi Detayı</h1>
        <a href="{{ route('admin.borrowings.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Listeye Dön
        </a>
    </div>
    
    <!-- Ödünç Detayları -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded-md">
            <h2 class="text-lg font-medium text-gray-800 mb-4">İşlem Bilgileri</h2>
            <dl class="grid grid-cols-1 gap-y-3">
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">İşlem Tarihi:</dt>
                    <dd class="text-sm text-gray-900">{{ Carbon\Carbon::parse($borrowing->created_at)->format('d.m.Y H:i') }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Ödünç Verme Tarihi:</dt>
                    <dd class="text-sm text-gray-900">{{ Carbon\Carbon::parse($borrowing->borrow_date)->format('d.m.Y') }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Son Teslim Tarihi:</dt>
                    <dd class="text-sm text-gray-900">{{ Carbon\Carbon::parse($borrowing->due_date)->format('d.m.Y') }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">İade Tarihi:</dt>
                    <dd class="text-sm text-gray-900">
                        {{ $borrowing->return_date ? Carbon\Carbon::parse($borrowing->return_date)->format('d.m.Y') : 'İade Edilmedi' }}
                    </dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Durum:</dt>
                    <dd class="text-sm text-gray-900">
                        @php
                            $today = \Carbon\Carbon::now();
                            $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
                            
                            if ($borrowing->return_date) {
                                $statusClass = 'bg-green-100 text-green-800';
                                $statusText = 'İade Edildi';
                            } elseif ($today->gt($dueDate)) {
                                $statusClass = 'bg-red-100 text-red-800';
                                $statusText = 'Gecikmiş';
                            } else {
                                $statusClass = 'bg-blue-100 text-blue-800';
                                $statusText = 'Ödünç Verildi';
                            }
                        @endphp
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-md">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Kullanıcı Bilgileri</h2>
            <dl class="grid grid-cols-1 gap-y-3">
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Kullanıcı Adı:</dt>
                    <dd class="text-sm text-gray-900">{{ $borrowing->user->name }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">E-posta:</dt>
                    <dd class="text-sm text-gray-900">{{ $borrowing->user->email }}</dd>
                </div>
                <div class="grid grid-cols-2">
                    <dt class="text-sm font-medium text-gray-500">Telefon:</dt>
                    <dd class="text-sm text-gray-900">{{ $borrowing->user->phone ?? 'Belirtilmemiş' }}</dd>
                </div>
                
                <!-- Kullanıcının Aktif Ödünç İşlemleri -->
                <div class="col-span-2 mt-2">
                    <h3 class="text-sm font-medium text-gray-600 mb-1">Aktif Ödünç Kitapları</h3>
                    <div class="border rounded-md overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kitap</th>
                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $activeLoans = \App\Models\Borrowing::where('user_id', $borrowing->user_id)
                                            ->whereNull('return_date')
                                            ->with('stock.book')
                                            ->get();
                                @endphp
                                
                                @forelse($activeLoans as $loan)
                                    <tr>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ $loan->stock->book->title ?? $loan->stock->book->name }}
                                        </td>
                                        <td class="px-3 py-2 text-xs text-gray-900">
                                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d.m.Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-2 text-xs text-gray-500 text-center">Aktif ödünç alınan kitap yok</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- Kitap Bilgileri -->
    <div class="bg-gray-50 p-4 rounded-md mb-6">
        <h2 class="text-lg font-medium text-gray-800 mb-4">Kitap Bilgileri</h2>
        <div class="flex flex-col md:flex-row md:space-x-6">
            <div class="md:w-1/3 flex justify-center mb-4 md:mb-0">
                <div class="w-48 h-64 bg-gray-300 rounded-md flex items-center justify-center">
                    @if(isset($borrowing->stock->book->cover_image) && $borrowing->stock->book->cover_image)
                        <img src="{{ asset('storage/' . $borrowing->stock->book->cover_image) }}" alt="{{ $borrowing->stock->book->title ?? 'Kitap Kapağı' }}" class="h-full w-full object-cover rounded-md">
                    @else
                        <span class="text-gray-500"><i class="fas fa-book fa-4x"></i></span>
                    @endif
                </div>
            </div>
            
            <div class="md:w-2/3">
                <dl class="grid grid-cols-1 gap-y-3">
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">Kitap Adı:</dt>
                        <dd class="text-sm text-gray-900">{{ $borrowing->stock->book->title ?? $borrowing->stock->book->name }}</dd>
                    </div>
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">Yazar(lar):</dt>
                        <dd class="text-sm text-gray-900">
                            @if(isset($borrowing->stock->book->authors) && $borrowing->stock->book->authors->count() > 0)
                                {{ $borrowing->stock->book->authors->pluck('name')->join(', ') }}
                            @else
                                Belirtilmemiş
                            @endif
                        </dd>
                    </div>
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">Yayınevi:</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $borrowing->stock->book->publisher->name ?? 'Belirtilmemiş' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">ISBN:</dt>
                        <dd class="text-sm text-gray-900">{{ $borrowing->stock->book->isbn ?? 'Belirtilmemiş' }}</dd>
                    </div>
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">Kategori:</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $borrowing->stock->book->category->name ?? 'Belirtilmemiş' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">Tür:</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $borrowing->stock->book->genre->name ?? 'Belirtilmemiş' }}
                        </dd>
                    </div>
                    <div class="grid grid-cols-2">
                        <dt class="text-sm font-medium text-gray-500">Barkod:</dt>
                        <dd class="text-sm text-gray-900">{{ $borrowing->stock->barcode ?? 'Belirtilmemiş' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- İşlem Butonları -->
    <div class="flex justify-end space-x-3">
        @if(!$borrowing->return_date)
            <button type="button" onclick="openReturnModal({{ $borrowing->id }})" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                <i class="fas fa-undo mr-2"></i> İade Al
            </button>
        @endif
    </div>
</div>

<!-- Kitap İade Modal -->
<div id="returnBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-bold">Kitap İade</h3>
            <button onclick="document.getElementById('returnBookModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="returnBookForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">İade Tarihi</label>
                <input type="date" name="return_date" id="return_date" value="{{ date('Y-m-d') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('returnBookModal').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    İptal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    İade Et
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReturnModal(borrowingId) {
        // Form action URL'sini ayarla
        document.getElementById('returnBookForm').action = `/admin/borrowings/${borrowingId}/return`;
        
        // Modal'ı göster
        document.getElementById('returnBookModal').classList.remove('hidden');
    }
</script>
@endsection 
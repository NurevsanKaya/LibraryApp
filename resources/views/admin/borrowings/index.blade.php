@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Başarı/Hata Mesajları -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex justify-between items-center" role="alert">
            <p>{{ session('success') }}</p>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 flex justify-between items-center" role="alert">
            <p>{{ session('error') }}</p>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 flex justify-between items-center" role="alert">
            <p>{{ session('warning') }}</p>
            <button type="button" onclick="this.parentElement.remove()" class="text-yellow-700 hover:text-yellow-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Kitap Ödünç İşlemleri</h1>
        <!-- Kitap Ödünç Ver Butonu -->
        <a href="{{ route('admin.borrowings.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            <i class="fas fa-plus-circle mr-2"></i> Kitap Ödünç Ver
        </a>
    </div>

    <!-- Filtreler -->
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" id="borrowingSearch" placeholder="Arama yapabilirsiniz..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
        </div>
        <div class="flex gap-4">
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tüm Durumlar</option>
                <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Ödünç Verilmiş</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>İade Edilmiş</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Gecikmiş</option>
            </select>
            <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md" onclick="filterBorrowings()">
                <i class="fas fa-filter mr-2"></i> Filtrele
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Kitap Adı
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Raf
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Kullanıcı
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Ödünç Alınma Tarihi
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Teslim Tarihi
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Uzatma Tarihi
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        İade Tarihi
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Durum
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        İşlemler
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $borrowing)
                    <tr>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $borrowing->stock->book->name }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            @if($borrowing->stock->shelf)
                                {{ $borrowing->stock->shelf->shelf_number ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $borrowing->user->name }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d.m.Y') }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d.m.Y') }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            {{ $borrowing->extended_return_date ? \Carbon\Carbon::parse($borrowing->extended_return_date)->format('d.m.Y') : '-' }}
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            {{ $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date)->format('d.m.Y') : '-' }}
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            @php
                                $today = \Carbon\Carbon::now();
                                $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
                                $extendedDate = $borrowing->extended_return_date ? \Carbon\Carbon::parse($borrowing->extended_return_date) : null;
                                $statusClass = '';
                                $statusText = '';

                                if ($borrowing->return_date) {
                                    $statusClass = 'bg-green-100 text-green-800';
                                    $statusText = 'İade Edildi';
                                } elseif ($extendedDate && $today->gt($extendedDate)) {
                                    $statusClass = 'bg-red-100 text-red-800';
                                    $statusText = 'Gecikmiş';
                                } elseif ($today->gt($dueDate) && !$extendedDate) {
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
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            <div class="flex flex-col space-y-2">
                                @if(!$borrowing->return_date)<!--iade edilmemiş-->
                                    <button type="button"
                                            onclick="openReturnModal({{ $borrowing->id }})"
                                            class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-undo mr-1"></i> İade Al
                                    </button>
                                    
                                    @if(!$borrowing->extended_return_date)<!--süre uzatılmamış-->
                                        <button type="button"
                                                onclick="extendDueDate({{ $borrowing->id }})"
                                                class="text-green-500 hover:text-green-700">
                                            <i class="fas fa-calendar-plus mr-1"></i> Süreyi Uzat
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">Süre zaten uzatılmış</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">İşlem Yapılamaz</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-4 px-4 border-b border-gray-200 text-center text-gray-500">
                            Kayıt bulunamadı
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $borrowings->links() }}
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

    function extendDueDate(borrowingId) {
        // Kullanıcıya onay sor
        if (confirm("Bu kitabın iade süresi 7 gün uzatılacak. Emin misiniz?")) {
            // Form oluştur
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/borrowings/${borrowingId}/extend`;
            
            // CSRF token ekle
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Method ekle
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'POST';
            form.appendChild(methodInput);
            
            // Formu sayfaya ekle ve gönder
            document.body.appendChild(form);
            form.submit();
        }
    }

    function filterBorrowings() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('borrowingSearch').value;
        window.location.href = `/admin/borrowings?status=${status}&search=${search}`;
    }

    // Enter tuşuna basıldığında filtreleme işlemini gerçekleştir
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('borrowingSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterBorrowings();
            }
        });
    });
</script>
@endsection

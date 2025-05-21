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
            <label class="block text-sm font-medium text-gray-700 mb-1 text-white">.</label>
            <input type="text" id="borrowingSearch" placeholder="Arama yapabilirsiniz..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ request('search') }}">
        </div>
        <!-- Tarih Aralığı Filtreleri -->
        <div class="flex gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi</label>
                <div class="flex items-center gap-2">
                    <input type="date" id="startDate" name="start_date" value="{{ request('start_date') }}" 
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="relative">
                        <i class="fas fa-info-circle text-gray-400 cursor-help hover:text-gray-600" 
                           title="Bu tarihten sonra ödünç alınan kitaplar"></i>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi</label>
                <div class="flex items-center gap-2">
                    <input type="date" id="endDate" name="end_date" value="{{ request('end_date') }}" 
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="relative">
                        <i class="fas fa-info-circle text-gray-400 cursor-help hover:text-gray-600" 
                           title="Bu tarihten önce ödünç alınan kitaplar"></i>
                    </div>
                </div>
            </div>
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
                        <td class="py-4 px-4 border-b border-gray-200">
                            @if($borrowing->stock && $borrowing->stock->book)
                                {{ $borrowing->stock->book->name }}
                            @else
                                Silinmiş Kitap
                            @endif
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            @if($borrowing->stock && $borrowing->stock->shelf)
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
                                                onclick="extendDueDate({{ $borrowing->id }}, '{{ $borrowing->due_date }}')"
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

<!-- Süre Uzatma Modal -->
<div id="extendDueDateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-bold">Süre Uzatma</h3>
            <button onclick="document.getElementById('extendDueDateModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="extendDueDateForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="extension_days" class="block text-sm font-medium text-gray-700 mb-1">Kaç gün uzatmak istiyorsunuz?</label>
                <input type="number" name="extension_days" id="extension_days" value="7" min="1" max="30" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">En fazla 30 gün uzatabilirsiniz.</p>
            </div>

            <div class="mb-4">
                <label for="extended_date" class="block text-sm font-medium text-gray-700 mb-1">Yeni Teslim Tarihi</label>
                <input type="date" name="extended_date" id="extended_date" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <input type="hidden" id="original_due_date" name="original_due_date">
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('extendDueDateModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                    İptal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Süreyi Uzat
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

    function extendDueDate(borrowingId, dueDate) {
        // Form action URL'sini ayarla
        document.getElementById('extendDueDateForm').action = `/admin/borrowings/${borrowingId}/extend`;

        // Orijinal teslim tarihini kaydet
        const originalDueDate = document.getElementById('original_due_date');
        originalDueDate.value = dueDate;

        // Varsayılan uzatma süresi (7 gün)
        const extensionDays = document.getElementById('extension_days');
        extensionDays.value = 7;

        // Yeni tarihi hesapla (orijinal tarih + 7 gün)
        const newDate = calculateNewDate(dueDate, 7);
        document.getElementById('extended_date').value = newDate;

        // Minimum tarihi bugün olarak ayarla
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('extended_date').min = today;

        // Modal'ı göster
        document.getElementById('extendDueDateModal').classList.remove('hidden');
    }

    // Gün sayısı değiştiğinde yeni tarihi hesapla
    document.getElementById('extension_days').addEventListener('change', function() {
        const originalDueDate = document.getElementById('original_due_date').value;
        const days = parseInt(this.value);

        if (days > 0 && days <= 30) {
            const newDate = calculateNewDate(originalDueDate, days);
            document.getElementById('extended_date').value = newDate;
        }
    });

    // Tarih değiştiğinde gün farkını hesapla
    document.getElementById('extended_date').addEventListener('change', function() {
        const originalDueDate = new Date(document.getElementById('original_due_date').value);
        const newDate = new Date(this.value);

        // İki tarih arasındaki farkı gün olarak hesapla
        const diffTime = Math.abs(newDate - originalDueDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        document.getElementById('extension_days').value = diffDays;
    });

    // Yeni tarihi hesaplama fonksiyonu
    function calculateNewDate(baseDate, daysToAdd) {
        const date = new Date(baseDate);
        date.setDate(date.getDate() + parseInt(daysToAdd));
        return date.toISOString().split('T')[0];
    }

    function filterBorrowings() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('borrowingSearch').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        const url = new URL(window.location.href);
        url.searchParams.set('status', status);
        url.searchParams.set('search', search);
        url.searchParams.set('start_date', startDate);
        url.searchParams.set('end_date', endDate);

        window.location.href = url.toString();
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

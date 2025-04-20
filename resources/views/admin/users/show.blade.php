@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Kullanıcı Detayları</h1>
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Listeye Dön
        </a>
    </div>

    <!-- Kullanıcı Bilgileri -->
    <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-user-circle mr-2 text-blue-500"></i> Kullanıcı Bilgileri
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Ad Soyad</p>
                <p class="text-lg font-medium">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">E-posta</p>
                <p class="text-lg">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Telefon</p>
                <p class="text-lg">{{ $user->phone ?? 'Belirtilmemiş' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Üyelik Tarihi</p>
                <p class="text-lg">{{ $user->created_at->format('d.m.Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Durum</p>
                <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Ödünç İstatistikleri -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 p-4 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-blue-800 mb-2">Aktif Ödünç</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $activeBorrowings->count() }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-yellow-800 mb-2">Yaklaşan Teslimler</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $upcomingDueDates->count() }}</p>
        </div>
        <div class="bg-red-50 p-4 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-red-800 mb-2">Gecikmiş</h3>
            <p class="text-3xl font-bold text-red-600">{{ $overdueBorrowings->count() }}</p>
        </div>
    </div>

    <!-- Aktif Ödünç Kitaplar -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-book-reader mr-2 text-blue-500"></i> Aktif Ödünç Kitaplar
        </h2>
        
        @if($activeBorrowings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kitap</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yazar</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barkod</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alınma Tarihi</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($activeBorrowings as $borrowing)
                            @php
                                $today = \Carbon\Carbon::now();
                                $dueDate = $borrowing->extended_return_date ? \Carbon\Carbon::parse($borrowing->extended_return_date) : \Carbon\Carbon::parse($borrowing->due_date);
                                $diffDays = $today->diffInDays($dueDate, false);
                                
                                // Durum sınıfını belirle
                                if ($diffDays < 0) {
                                    $statusClass = 'bg-red-100 text-red-800'; // Gecikmiş
                                    $statusText = 'Gecikmiş (' . abs(intval($diffDays)) . ' gün)';
                                } elseif ($diffDays <= 3) {
                                    $statusClass = 'bg-yellow-100 text-yellow-800'; // Yakında teslim
                                    $statusText = 'Yakında teslim (' . intval($diffDays) . ' gün)';
                                } else {
                                    $statusClass = 'bg-green-100 text-green-800'; // Normal
                                    $statusText = 'Normal (' . intval($diffDays) . ' gün)';
                                }
                            @endphp
                            <tr>
                                <td class="py-3 px-4 text-sm">{{ $borrowing->stock->book->name ?? 'Belirtilmemiş' }}</td>
                                <td class="py-3 px-4 text-sm">
                                    @if(isset($borrowing->stock->book->authors) && $borrowing->stock->book->authors->count() > 0)
                                        {{ $borrowing->stock->book->authors->pluck('first_name')->join(' ') }} {{ $borrowing->stock->book->authors->pluck('last_name')->join(' ') }}
                                    @else
                                        Belirtilmemiş
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $borrowing->stock->barcode ?? 'Belirtilmemiş' }}</td>
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d.m.Y') }}</td>
                                <td class="py-3 px-4 text-sm">
                                    @if($borrowing->extended_return_date)
                                        <span class="line-through text-gray-500">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d.m.Y') }}</span><br>
                                        <span>{{ \Carbon\Carbon::parse($borrowing->extended_return_date)->format('d.m.Y') }}</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d.m.Y') }}
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs">
                                    <span class="px-2 py-1 rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <a href="{{ route('admin.borrowings.show', $borrowing->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye mr-1"></i> Detay
                                    </a>
                                    @if(!$borrowing->extended_return_date && $diffDays >= 0)
                                        <a href="{{ route('admin.borrowings.show', $borrowing->id) }}" class="text-green-500 hover:text-green-700 ml-2">
                                            <i class="fas fa-calendar-plus mr-1"></i> Süre Uzat
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-50 p-4 rounded-md">
                <p class="text-blue-700">Bu kullanıcının henüz aktif ödünç aldığı kitap bulunmamaktadır.</p>
            </div>
        @endif
    </div>

    <!-- Geçmiş Ödünç İşlemleri -->
    <div>
        <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-history mr-2 text-blue-500"></i> Geçmiş Ödünç İşlemleri
        </h2>
        
        @if($pastBorrowings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kitap</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yazar</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barkod</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alınma Tarihi</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İade Tarihi</th>
                            <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($pastBorrowings as $borrowing)
                            @php
                                $dueDate = $borrowing->extended_return_date ? \Carbon\Carbon::parse($borrowing->extended_return_date) : \Carbon\Carbon::parse($borrowing->due_date);
                                $returnDate = \Carbon\Carbon::parse($borrowing->return_date);
                                $isLate = $returnDate->gt($dueDate);
                                
                                // Durum sınıfını belirle
                                if ($isLate) {
                                    $statusClass = 'bg-red-100 text-red-800'; // Geç iade
                                    $statusText = 'Geç İade (' . intval($returnDate->diffInDays($dueDate)) . ' gün)';
                                } else {
                                    $statusClass = 'bg-green-100 text-green-800'; // Zamanında
                                    $statusText = 'Zamanında İade';
                                }
                            @endphp
                            <tr>
                                <td class="py-3 px-4 text-sm">{{ $borrowing->stock->book->name ?? 'Belirtilmemiş' }}</td>
                                <td class="py-3 px-4 text-sm">
                                    @if(isset($borrowing->stock->book->authors) && $borrowing->stock->book->authors->count() > 0)
                                        {{ $borrowing->stock->book->authors->pluck('first_name')->join(' ') }} {{ $borrowing->stock->book->authors->pluck('last_name')->join(' ') }}
                                    @else
                                        Belirtilmemiş
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $borrowing->stock->barcode ?? 'Belirtilmemiş' }}</td>
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d.m.Y') }}</td>
                                <td class="py-3 px-4 text-sm">
                                    @if($borrowing->extended_return_date)
                                        <span class="line-through text-gray-500">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d.m.Y') }}</span><br>
                                        <span>{{ \Carbon\Carbon::parse($borrowing->extended_return_date)->format('d.m.Y') }}</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d.m.Y') }}
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">{{ \Carbon\Carbon::parse($borrowing->return_date)->format('d.m.Y') }}</td>
                                <td class="py-3 px-4 text-xs">
                                    <span class="px-2 py-1 rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-50 p-4 rounded-md">
                <p class="text-blue-700">Bu kullanıcının henüz iade ettiği kitap bulunmamaktadır.</p>
            </div>
        @endif
    </div>
</div>
@endsection 
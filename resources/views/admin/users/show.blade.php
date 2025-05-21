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
            <!-- Arama kutusu -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="activeSearchInput" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Kitap adı veya yazar ile ara...">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="overflow-y-auto" style="max-height: calc(4 * 53px);">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="sticky top-0 bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kitap</th>
                                <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yazar</th>
                                <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barkod</th>
                                <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alınma Tarihi</th>
                                <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                                <th class="py-3 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="activeTableBody">
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
                                <tr class="active-row">
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
                                    
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            <!-- Arama kutusu -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="pastSearchInput" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Kitap adı veya yazar ile ara...">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <div class="overflow-y-auto" style="max-height: calc(4 * 53px);">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="sticky top-0 bg-gray-50">
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
                        <tbody class="divide-y divide-gray-200" id="pastTableBody">
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
                                <tr class="past-row">
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
            </div>
        @else
            <div class="bg-blue-50 p-4 rounded-md">
                <p class="text-blue-700">Bu kullanıcının henüz iade ettiği kitap bulunmamaktadır.</p>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        // Türkçe karakter normalizasyonu
        function turkishToLower(text) {
            return text.replace(/İ/g, 'i')
                      .replace(/I/g, 'ı')
                      .replace(/Ğ/g, 'ğ')
                      .replace(/Ü/g, 'ü')
                      .replace(/Ş/g, 'ş')
                      .replace(/Ö/g, 'ö')
                      .replace(/Ç/g, 'ç')
                      .toLowerCase();
        }

        // Aktif ödünç kitaplar için arama
        $("#activeSearchInput").on("keyup", function() {
            var value = turkishToLower($(this).val());

            $("#activeTableBody tr").each(function() {
                // İlk iki sütundaki (kitap adı ve yazar) metni al
                var bookName = turkishToLower($(this).find("td:eq(0)").text());
                var author = turkishToLower($(this).find("td:eq(1)").text());

                // Kitap adı veya yazarda arama metni varsa göster, yoksa gizle
                if (bookName.indexOf(value) > -1 || author.indexOf(value) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Geçmiş ödünç kitaplar için arama
        $("#pastSearchInput").on("keyup", function() {
            var value = turkishToLower($(this).val());

            $("#pastTableBody tr").each(function() {
                // İlk iki sütundaki (kitap adı ve yazar) metni al
                var bookName = turkishToLower($(this).find("td:eq(0)").text());
                var author = turkishToLower($(this).find("td:eq(1)").text());

                // Kitap adı veya yazarda arama metni varsa göster, yoksa gizle
                if (bookName.indexOf(value) > -1 || author.indexOf(value) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection

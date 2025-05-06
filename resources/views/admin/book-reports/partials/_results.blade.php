@if($books->isEmpty())
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-search fa-3x mb-4"></i>
        <p>Sonuç bulunamadı.</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    {{-- Temel sütunlar (her sorgu için) --}}
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kitap Adı</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yazarlar</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barkod</th>

                    {{-- Gecikmiş ve Bugün İade sorguları için ek sütunlar --}}
                    @if(in_array($queryType ?? '', ['overdue', 'due_today']))
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gecikme</th>
                    @endif

                    {{-- En çok ödünç alınanlar için ek sütunlar --}}
                    @if($queryType === 'most_borrowed')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödünç Alma Sayısı</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son Ödünç Alma</th>
                    @endif

                    {{-- Son 1 ayda eklenenler için ek sütunlar --}}
                    @if($queryType === 'last_month')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eklenme Tarihi</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Edinme Bedeli</th>
                    @endif

                    {{-- Hiç ödünç alınmayanlar için ek sütunlar --}}
                    @if($queryType === 'never_borrowed')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eklenme Tarihi</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rafta Bekleme Süresi</th>
                    @endif

                    {{-- Rafta mevcut olanlar için ek sütunlar --}}
                    @if($queryType === 'available')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                    @endif

                    {{-- Aktif ödünç işlemleri için ek sütunlar --}}
                    @if($queryType === 'active_borrowings')
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödünç Alma</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teslim Tarihi</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kalan Süre</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($books as $book)
                    <tr class="hover:bg-gray-50">
                        {{-- Temel sütunlar (her sorgu için) --}}
                        <td class="px-4 py-2">
                            <div class="text-sm font-medium text-gray-900">{{ $book->name }}</div>
                            <div class="text-xs text-gray-500">ISBN: {{ $book->isbn }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm text-gray-900">
                                @foreach($book->authors as $author)
                                    {{ $author->first_name }} {{ $author->last_name }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm text-gray-900">{{ $book->category->name ?? 'Belirtilmemiş' }}</div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="text-sm text-gray-900">{{ $book->barcode ?? 'Barkod Yok' }}</div>
                        </td>

                        {{-- Gecikmiş ve Bugün İade sorguları için ek sütunlar --}}
                        @if(in_array($queryType ?? '', ['overdue', 'due_today']))
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($book->due_date)->format('d.m.Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $today = \Carbon\Carbon::now();
                                    $dueDate = \Carbon\Carbon::parse($book->due_date);
                                    $diffDays = (int)$today->diffInDays($dueDate, false);
                                @endphp
                                <div class="text-sm {{ $diffDays < 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    @if($diffDays < 0)
                                        {{ abs($diffDays) }} gün gecikmiş
                                    @else
                                        Bugün teslim
                                    @endif
                                </div>
                            </td>
                        @endif

                        {{-- En çok ödünç alınanlar için ek sütunlar --}}
                        @if($queryType === 'most_borrowed')
                            <td class="px-4 py-2">
                                <div class="text-sm font-medium text-blue-600">
                                    {{ $book->borrow_count }} kez
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($book->last_borrowed_date)->format('d.m.Y') }}
                                </div>
                            </td>
                        @endif

                        {{-- Son 1 ayda eklenenler için ek sütunlar --}}
                        @if($queryType === 'last_month')
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($book->stock_created_at)->format('d.m.Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ number_format($book->acquisition_price, 2) }} ₺
                                </div>
                            </td>
                        @endif

                        {{-- Hiç ödünç alınmayanlar için ek sütunlar --}}
                        @if($queryType === 'never_borrowed')
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($book->stock_created_at)->format('d.m.Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $createdAt = \Carbon\Carbon::parse($book->stock_created_at);
                                    $waitingDays = (int)$createdAt->diffInDays(now());
                                @endphp
                                <div class="text-sm text-gray-900">
                                    {{ $waitingDays }} gün
                                </div>
                            </td>
                        @endif

                        {{-- Rafta mevcut olanlar için ek sütunlar --}}
                        @if($queryType === 'available')
                            <td class="px-4 py-2">
                                <div class="text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        Rafta
                                    </span>
                                </div>
                            </td>
                        @endif

                        {{-- Aktif ödünç işlemleri için ek sütunlar --}}
                        @if($queryType === 'active_borrowings')
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($book->borrow_date)->format('d.m.Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($book->due_date)->format('d.m.Y') }}
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $today = \Carbon\Carbon::now();
                                    $dueDate = \Carbon\Carbon::parse($book->due_date);
                                    $remainingDays = (int)$today->diffInDays($dueDate, false);
                                @endphp
                                <div class="text-sm {{ $remainingDays < 0 ? 'text-red-600' : ($remainingDays <= 3 ? 'text-yellow-600' : 'text-green-600') }}">
                                    @if($remainingDays < 0)
                                        {{ abs($remainingDays) }} gün gecikmiş
                                    @else
                                        {{ $remainingDays }} gün kaldı
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $books->links() }}
    </div>
@endif 
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kitap Adı
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    ISBN
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Yazar(lar)
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kategori
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Yayınevi
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Basım Yılı
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Durum
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ödünç Sayısı
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($books as $book)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $book->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $book->isbn }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @foreach($book->authors as $author)
                                {{ $author->name }} {{ $author->surname }}@if(!$loop->last), @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $book->category->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $book->publisher->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $book->publication_year }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $status = 'Rafta';
                            $statusClass = 'bg-green-100 text-green-800';
                            
                            if ($book->stocks->where('status', 'borrowed')->count() > 0) {
                                $status = 'Ödünçte';
                                $statusClass = 'bg-blue-100 text-blue-800';
                            } elseif ($book->stocks->where('status', 'reserved')->count() > 0) {
                                $status = 'Rezerve';
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                            }
                            
                            // Gecikmiş kontrolü
                            $hasOverdue = $book->stocks->flatMap->borrowings
                                ->where('return_date', null)
                                ->where('due_date', '<', now())
                                ->count() > 0;
                                
                            if ($hasOverdue) {
                                $status = 'Gecikmiş';
                                $statusClass = 'bg-red-100 text-red-800';
                            }
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $book->stocks->flatMap->borrowings->count() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        Sonuç bulunamadı.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="px-6 py-4">
    {{ $books->links() }}
</div> 
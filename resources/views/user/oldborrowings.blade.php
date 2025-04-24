@extends('layouts.dashboard')
@section('title', 'My Borrowings')
@section('content')
    <div class="max-w-6xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6">📖 Geçmiş Ödünç Aldıklarım</h2>

        @if($borrowings->isEmpty())
            <p class="text-gray-600">Geçmiş ödünç aldığınız kitap bulunmamaktadır.</p>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left">Kitap</th>
                        <th class="px-6 py-4 text-left">Yazar(lar)</th>
                        <th class="px-6 py-4 text-left">Kategori</th>
                        <th class="px-6 py-4 text-left">Ödünç Tarihi</th>
                        <th class="px-6 py-4 text-left">Teslim Tarihi</th>
                        <th class="px-6 py-4 text-left">Gerçek İade</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @foreach($borrowings as $item)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $item->stock->book->name }}</td>
                            <td class="px-6 py-4">
                                @foreach($item->stock->book->authors as $author)
                                    {{ $author->first_name }} {{ $author->last_name }}@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td class="px-6 py-4">{{ $item->stock->book->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $item->borrow_date->format('d.m.Y') }}</td>
                            <td class="px-6 py-4">{{ $item->due_date->format('d.m.Y') }}</td>
                            <td class="px-6 py-4 text-green-600 font-semibold">
                                {{ $item->return_date ? $item->return_date->format('d.m.Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>


@endsection

@extends('layouts.dashboard')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Geçikmiş Kitaplarım</h1>

        @if($overdueBooks->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
                <p class="text-gray-600 text-lg">Geçikmiş kitabınız bulunmamaktadır.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kitap Bilgileri</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ödünç Tarihi</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İade Tarihi</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gecikme</th>
                            <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ceza Bilgisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overdueBooks as $book)
                            <tr>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <div class="text-sm font-medium text-gray-900">{{ $book['book_name'] }}</div>
                                    <div class="text-xs text-gray-500">ISBN: {{ $book['isbn'] }}</div>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-200">{{ $book['category'] }}</td>
                                <td class="py-3 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($book['borrow_date'])->format('d.m.Y') }}</td>
                                <td class="py-3 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($book['due_date'])->format('d.m.Y') }}</td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <span class="px-2 py-1 text-sm font-semibold text-red-600 bg-red-100 rounded-full">
                                        {{ number_format($book['days_overdue']) }} gün
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b border-gray-200">
                                    <div class="text-sm">
                                        <div class="text-gray-600">Temel Ceza: {{ number_format($book['base_penalty']) }} TL</div>
                                        <div class="text-gray-600">Günlük: {{ number_format($book['daily_penalty']) }} TL</div>
                                        <div class="font-semibold text-red-600">Toplam: {{ number_format($book['current_penalty']) }} TL</div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection 
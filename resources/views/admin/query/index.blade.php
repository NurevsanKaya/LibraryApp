@extends('layouts.admin')

@section('content')
    <form method="GET" action="{{ route('admin.query.index') }}" class="p-6 bg-white rounded-lg shadow-md space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Yayınevi -->
            <div>
                <label for="publisher" class="block text-sm font-medium text-gray-700 mb-1">Yayınevi</label>
                <input type="text" id="publisher" name="publisher" value="{{ request('publisher') }}" class="w-full border-gray-300 rounded-md shadow-sm p-2" placeholder="Yayınevi adı girin">
            </div>

            <!-- Edinme Kaynağı -->
            <div>
                <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Edinme Kaynağı</label>
                <select id="source" name="source" class="w-full border-gray-300 rounded-md shadow-sm p-2">
                    <option value="">Seçiniz</option>
                    <option value="Satın Alma" {{ request('source') == 'Satın Alma' ? 'selected' : '' }}>Satın Alma</option>
                    <option value="Bağış" {{ request('source') == 'Bağış' ? 'selected' : '' }}>Bağış</option>
                    <option value="Diğer" {{ request('source') == 'Diğer' ? 'selected' : '' }}>Diğer</option>
                </select>
            </div>

            <!-- Başlangıç Tarihi -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <!-- Bitiş Tarihi -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <!-- ISBN -->
            <div>
                <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" id="isbn" name="isbn" value="{{ request('isbn') }}" class="w-full border-gray-300 rounded-md shadow-sm p-2" placeholder="ISBN numarası girin">
            </div>

        </div>

        <!-- Ara Butonu -->
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                Listele
            </button>
        </div>
    </form>

@endsection

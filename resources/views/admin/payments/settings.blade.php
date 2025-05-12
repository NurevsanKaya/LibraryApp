@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Ceza Ayarları</h1>
            
            <a href="{{ route('admin.payments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                <i class="fas fa-arrow-left mr-1"></i> Ceza Listesine Dön
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.penalty.settings.update') }}" method="POST">
            @csrf
            <div class="mb-6">
                <h2 class="text-lg font-medium text-gray-700 mb-3">Ceza Hesaplama Parametreleri</h2>
                <p class="text-sm text-gray-500 mb-4">
                    Bu ayarlar sadece yeni oluşturulacak cezalarda geçerli olacaktır.
                    Mevcut ceza kayıtları etkilenmeyecektir.
                </p>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="base_penalty_fee" class="block text-sm font-medium text-gray-700 mb-1">
                            Temel Ceza Tutarı (₺)
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₺</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="base_penalty_fee" id="base_penalty_fee" 
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                placeholder="0.00" value="{{ old('base_penalty_fee', $settings->base_penalty_fee) }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Her gecikme için sabit olarak uygulanacak temel ceza tutarı.
                        </p>
                        @error('base_penalty_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="daily_penalty_fee" class="block text-sm font-medium text-gray-700 mb-1">
                            Günlük Gecikme Cezası (₺)
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₺</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="daily_penalty_fee" id="daily_penalty_fee" 
                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                placeholder="0.00" value="{{ old('daily_penalty_fee', $settings->daily_penalty_fee) }}">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Her gecikmeli gün için eklenecek ceza tutarı.
                        </p>
                        @error('daily_penalty_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-save mr-1"></i> Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>
@endsection 
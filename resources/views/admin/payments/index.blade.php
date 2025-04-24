@extends('layouts.admin')

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Ceza İşlemleri</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tablo -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-sm text-gray-700">
                <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Kullanıcı</th>
                    <th class="px-4 py-3 text-left">Tutar</th>
                    <th class="px-4 py-3 text-left">Yöntem</th>
                    <th class="px-4 py-3 text-left">Durum</th>
                    <th class="px-4 py-3 text-left">Tarih</th>
                    <th class="px-4 py-3 text-left">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse($penalties as $penalty)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $penalty->user->name }}</td>
                        <td class="px-4 py-3">{{ $penalty->amount }} ₺</td>
                        <td class="px-4 py-3">{{ ucfirst($penalty->payment_method) }}</td>
                        <td class="px-4 py-3">
                            @if($penalty->status === 'onaylandı')
                                <span class="bg-green-100 text-green-800 px-2 py-1 text-xs rounded-full">Onaylandı</span>
                            @elseif($penalty->status === 'bekliyor')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs rounded-full">Bekliyor</span>
                            @elseif($penalty->status === 'reddedildi')
                                <span class="bg-red-100 text-red-800 px-2 py-1 text-xs rounded-full">Reddedildi</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 text-xs rounded-full">Bekleniyor</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($penalty->payment_date)->format('d.m.Y') }}</td>
                        <td class="px-4 py-3 space-y-2">
                            @if($penalty->payment_method === 'havale' && $penalty->receipt_path)
                                @if(Str::endsWith($penalty->receipt_path, ['.pdf']))
                                    <!-- PDF için buton -->
                                    <a href="{{ asset('storage/' . $penalty->receipt_path) }}" target="_blank"
                                       class="text-blue-600 hover:underline text-xs block">📎 Dekontu Görüntüle (PDF)</a>
                                @elseif(Str::endsWith($penalty->receipt_path, ['.jpg', '.jpeg', '.png']))
                                    <!-- Görsel dosyası için buton -->
                                    <button type="button" class="text-blue-600 hover:underline text-xs block" onclick="showImage('{{ asset('storage/' . $penalty->receipt_path) }}')">
                                        🖼️ Dekontu Görüntüle
                                    </button>
                                    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 justify-center items-center">
                                        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md mx-auto mt-24">
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-lg font-bold">Dekont Görüntüle</h3>
                                                <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
                                            </div>
                                            <img id="modalImage" src="" alt="Dekont" class="w-full h-auto rounded">
                                        </div>
                                    </div>
                                @else
                                    <!-- Diğer dosya türleri için uyarı -->
                                    <span class="text-red-600">Desteklenmeyen dosya türü!</span>
                                @endif
                            @endif
                            {{-- Onay / Red işlemleri --}}
                            @if($penalty->status === 'bekliyor')
                                <form action="{{ route('admin.penalty.approve', $penalty->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-600 hover:underline text-xs" onclick="return confirm('Bu cezayı onaylamak istiyor musunuz?')">
                                        ✅ Onayla
                                    </button>
                                </form>

                                <form action="{{ route('admin.penalty.reject', $penalty->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-red-600 hover:underline text-xs" onclick="return confirm('Bu cezayı reddetmek istiyor musunuz?')">
                                        ❌ Reddet
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-500 text-xs block">İşlem tamamlandı</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Kayıtlı ceza bulunamadı.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <script>


            function showImage(imagePath) {
            // Modalı göster
            document.getElementById('imageModal').classList.remove('hidden');
            // Görseli modalda göster
            document.getElementById('modalImage').src = imagePath;
            }

            function closeImageModal() {
            // Modalı gizle
            document.getElementById('imageModal').classList.add('hidden');
            }
            </script>

        </div>
    </div>

@endsection

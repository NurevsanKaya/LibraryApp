@extends('layouts.dashboard')

@section('title', 'My Penalties')

@section('content')
    <h2 class="text-2xl font-bold mb-6">💸 Cezalarım</h2>

    <ul class="space-y-4">
        @foreach($penalties as $penalty)
            <li class="bg-white p-4 rounded shadow relative">
                <div>
                    <strong>Tutar:</strong> {{ $penalty->amount }} ₺<br>
                    <strong>Durum:</strong>
                    @if($penalty->status === 'onaylandı')
                        <span class="text-green-600">✔ Ödeme Onaylandı</span>
                    @elseif($penalty->status === 'bekliyor')
                        <span class="text-yellow-600">⏳ Onay Bekleniyor</span>
                    @elseif($penalty->status === 'reddedildi')
                        <span class="text-red-600">❌ Dekont Reddedildi</span>
                    @else
                        <span class="text-red-600">❌ Ödeme Bekleniyor</span>
                    @endif
                    <br>
                    <strong>Ödeme Tarihi:</strong> {{ $penalty->payment_date->format('d.m.Y') }}
                </div>
                @if(in_array($penalty->status, ['ödeme bekleniyor', null]))
                    <button onclick="openPaymentModal({{ $penalty->id }})"
                            class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">
                        Ödeme Yap
                    </button>
                @endif
            </li>

            <!-- Modal: Her ceza için -->
            <div id="paymentModal_{{ $penalty->id }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 justify-center items-center">
                <div class="bg-white p-6 rounded shadow-lg w-full max-w-md mx-auto mt-24">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Ödeme Seçenekleri</h3>
                        <button onclick="closePaymentModal({{ $penalty->id }})" class="text-gray-500 hover:text-gray-800">&times;</button>
                    </div>



                    <form action="{{ route('penalty.pay', $penalty->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <label>Ödeme Yöntemi:</label>
                        <select name="payment_method" class="w-full p-2 border rounded mt-1" required onchange="handlePaymentMethod(this.value, {{ $penalty->id }})">
                            <option value="" selected disabled>Seçiniz</option>
                            <option value="nakit">Nakit</option>
                            <option value="havale">Havale</option>
                        </select>

                        <div id="nakitMessage_{{ $penalty->id }}" class="text-blue-600 mt-3 hidden">
                            💬 Nakit ödeme memura iletildi. Onay bekleniyor.
                        </div>

                        <div id="havaleSection_{{ $penalty->id }}" class="mt-4 hidden">
                            <label>Dekont Yükle:</label>
                            <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" class="w-full p-2 border rounded mt-1" >
                        </div>

                        <button type="submit"
                                class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Gönder
                        </button>
                    </form>
                    <script>
                        function openPaymentModal(id) {
                            document.getElementById('paymentModal_' + id).classList.remove('hidden');
                        }

                        function closePaymentModal(id) {
                            document.getElementById('paymentModal_' + id).classList.add('hidden');
                        }

                        function handlePaymentMethod(value, id) {
                            const nakitMessage = document.getElementById('nakitMessage_' + id);
                            const havaleSection = document.getElementById('havaleSection_' + id);
                            const receiptInput = document.getElementById('receipt_input_' + id);

                            if (value === 'nakit') {
                                nakitMessage.classList.remove('hidden');
                                havaleSection.classList.add('hidden');
                                receiptInput.disabled = true;
                            } else if (value === 'havale') {
                                nakitMessage.classList.add('hidden');
                                havaleSection.classList.remove('hidden');
                                receiptInput.disabled = false;
                            } else {
                                nakitMessage.classList.add('hidden');
                                havaleSection.classList.add('hidden');
                                receiptInput.disabled = true;
                            }


                        }
                    </script>
                </div>
            </div>
        @endforeach
    </ul>

@endsection

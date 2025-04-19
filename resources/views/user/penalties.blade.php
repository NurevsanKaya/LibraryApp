@extends('layouts.dashboard')

@section('title', 'My Penalties')

@section('content')
    <h2 class="text-2xl font-bold mb-6">💸 Cezalarım</h2>

    @if($penalties->isEmpty())
        <p class="text-gray-600">Hiçbir cezanız yok. 🎉</p>
    @else
        <ul class="space-y-4">
            @foreach($penalties as $penalty)
                <li class="bg-white p-4 rounded shadow">
                    <strong>Tutar:</strong> {{ $penalty->amount }} ₺<br>
                    <strong>Durum:</strong>
                    @if($penalty->status === 'ödendi')
                        <span class="text-green-600">ödendi</span>
                    @else
                        <span class="text-red-600">ödeme bekleniyor</span>

                        <form action="{{ route('penalty.pay', $penalty->id) }}" method="POST" class="mt-2">
                            @csrf

                            <label for="payment_method" class="text-sm text-gray-700">Ödeme Yöntemi:</label>
                            <select name="payment_method" id="payment_method" required
                                    class="block w-full mt-1 p-2 border border-gray-300 rounded">
                                <option value="" disabled selected>Seçiniz</option>
                                <option value="nakit">Nakit</option>
                                <option value="online">Online</option>
                            </select>

                            <button type="submit"
                                    class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                Ödeme Yap
                            </button>
                        </form>
                    @endif
                    <br>
                    <strong>Ödeme Tarihi:</strong> {{ $penalty->payment_date->format('d.m.Y') }}
                </li>
            @endforeach
        </ul>
    @endif
@endsection

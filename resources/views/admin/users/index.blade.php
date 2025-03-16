@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Kullanıcı Listesi</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Ad Soyad
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        E-posta
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Telefon
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Üyelik Tarihi
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Durum
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $user->name }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $user->email }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $user->phone ?? '-' }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $user->created_at->format('d.m.Y') }}</td>
                        <!--<td class="py-4 px-4 border-b border-gray-200">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </td>-->
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 px-4 border-b border-gray-200 text-center text-gray-500">
                            Kullanıcı bulunamadı
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection 
@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Başarı/Hata Mesajları -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex justify-between items-center" role="alert">
            <p>{{ session('success') }}</p>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Mesajlar</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Gönderen
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        E-posta
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Konu
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Mesaj
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Durum
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Yanıt Tarihi
                    </th>
                    <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        İşlemler
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $message->name }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $message->email }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ $message->subject }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">{{ Str::limit($message->message, 50) }}</td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            <span class="px-2 py-1 text-xs rounded-full {{ $message->is_replied ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $message->is_replied ? 'Yanıtlandı' : 'Beklemede' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            @if($message->is_replied && $message->replies->isNotEmpty())
                                {{ $message->replies->last()->created_at->format('d.m.Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-4 px-4 border-b border-gray-200">
                            @if($message->is_replied)
                                <button type="button" onclick="showReplyViewModal('{{ $message->id }}')" class="text-green-500 hover:text-green-700 mr-2">
                                    <i class="fas fa-eye mr-1"></i> Yanıtı Gör
                                </button>
                                @if($message->replies->count() > 1)
                                    <button type="button" onclick="showReplyHistoryModal('{{ $message->id }}')" class="text-purple-500 hover:text-purple-700">
                                        <i class="fas fa-history mr-1"></i> Yanıt Geçmişi
                                    </button>
                                @endif
                            @endif
                            <button type="button" onclick="showReplyModal('{{ $message->id }}')" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-reply mr-1"></i> Yanıtla
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 px-4 border-b border-gray-200 text-center text-gray-500">
                            Mesaj bulunamadı
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Yanıt Modal -->
<div id="replyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-bold">Mesajı Yanıtla</h3>
            <button onclick="document.getElementById('replyModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="replyForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Orijinal Mesaj:</label>
                <div id="originalMessage" class="p-3 bg-gray-50 rounded-md text-gray-700"></div>
            </div>

            <div class="mb-4">
                <label for="reply" class="block text-sm font-medium text-gray-700 mb-1">Yanıtınız:</label>
                <textarea id="reply" name="reply" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" onclick="document.getElementById('replyModal').classList.add('hidden')" 
                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                    İptal
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Yanıtı Gönder
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Yanıt Görüntüleme Modal -->
<div id="replyViewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-bold">Son Yanıt</h3>
            <button onclick="document.getElementById('replyViewModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Orijinal Mesaj:</label>
            <div id="viewOriginalMessage" class="p-3 bg-gray-50 rounded-md text-gray-700"></div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Son Yanıt:</label>
            <div id="viewReply" class="p-3 bg-gray-50 rounded-md text-gray-700"></div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="button" onclick="document.getElementById('replyViewModal').classList.add('hidden')" 
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Kapat
            </button>
        </div>
    </div>
</div>

<!-- Yanıt Geçmişi Modal -->
<div id="replyHistoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-bold">Yanıt Geçmişi</h3>
            <button onclick="document.getElementById('replyHistoryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div id="historyReplies" class="space-y-4"></div>

        <div class="flex justify-end mt-6">
            <button type="button" onclick="document.getElementById('replyHistoryModal').classList.add('hidden')" 
                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Kapat
            </button>
        </div>
    </div>
</div>

<script>
function showReplyModal(messageId) {
    console.log('Modal açılıyor, mesaj ID:', messageId);
    
    const modal = document.getElementById('replyModal');
    const form = document.getElementById('replyForm');
    const originalMessage = document.getElementById('originalMessage');
    
    if (!modal || !form || !originalMessage) {
        console.error('Gerekli elementler bulunamadı');
        return;
    }
    
    // Mesaj detaylarını getir
    fetch(`/admin/messages/${messageId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Gelen veri:', data);
            originalMessage.textContent = data.message;
            form.action = `/admin/messages/${messageId}/reply`;
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Mesaj detayları yüklenirken bir hata oluştu. Lütfen sayfayı yenileyip tekrar deneyin.');
        });
}

function showReplyViewModal(messageId) {
    const modal = document.getElementById('replyViewModal');
    const originalMessage = document.getElementById('viewOriginalMessage');
    const reply = document.getElementById('viewReply');
    
    // Mesaj detaylarını getir
    fetch(`/admin/messages/${messageId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            originalMessage.textContent = data.message;
            // Son yanıtı göster
            if (data.replies && data.replies.length > 0) {
                const lastReply = data.replies[data.replies.length - 1];
                reply.textContent = lastReply.reply;
            } else {
                reply.textContent = 'Henüz yanıt verilmemiş';
            }
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Mesaj detayları yüklenirken bir hata oluştu. Lütfen sayfayı yenileyip tekrar deneyin.');
        });
}

function showReplyHistoryModal(messageId) {
    const modal = document.getElementById('replyHistoryModal');
    const replies = document.getElementById('historyReplies');
    
    // Mesaj detaylarını getir
    fetch(`/admin/messages/${messageId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Yanıtları göster
            replies.innerHTML = '';
            if (data.replies.length > 0) {
                data.replies.forEach((reply, index) => {
                    const replyDiv = document.createElement('div');
                    replyDiv.className = 'p-3 bg-gray-50 rounded-md border-l-4 border-purple-500';
                    replyDiv.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <div class="text-sm text-gray-500">Yanıt #${index + 1}</div>
                            <div class="text-sm text-gray-500">${reply.date}</div>
                        </div>
                        <div class="text-gray-700">${reply.reply}</div>
                    `;
                    replies.appendChild(replyDiv);
                });
            } else {
                replies.innerHTML = '<div class="text-gray-500 text-center">Henüz yanıt verilmemiş</div>';
            }
            
            modal.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Hata:', error);
            alert('Mesaj detayları yüklenirken bir hata oluştu. Lütfen sayfayı yenileyip tekrar deneyin.');
        });
}
</script>
@endsection 
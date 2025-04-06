@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Yeni Ödünç İşlemi</h1>
        <p class="text-gray-600 mt-1">Kullanıcıya kitap ödünç verin</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('admin.borrowings.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="book_id" id="selected_book_id">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kullanıcı Seçimi -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı <span class="text-red-500">*</span></label>
                <select name="user_id" id="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Kullanıcı Seçin</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Kitap Arama Bölümü -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kitap Ara (ISBN / İsim) <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="text" id="bookSearch" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="ISBN veya kitap adı...">
                    <button type="button" onclick="searchBook()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        <i class="fas fa-search mr-2"></i> Ara
                    </button>
                </div>
            </div>

            <!-- Kitap Arama Sonuçları -->
            <div id="bookSearchResults" class="md:col-span-2 mb-4 hidden">
                <div class="border rounded-md p-4">
                    <h4 class="font-medium mb-2">Bulunan Kitaplar:</h4>
                    <div id="bookResult" class="max-h-64 overflow-y-auto"></div>
                </div>
            </div>

            <!-- Seçilen Kitap Bilgileri -->
            <div id="selectedBookInfo" class="md:col-span-2 mb-4 hidden">
                <div class="border rounded-md p-4 bg-blue-50">
                    <h4 class="font-medium mb-2 text-blue-800">Seçilen Kitap:</h4>
                    <div id="selectedBookDetails" class="text-sm text-blue-600"></div>
                </div>
            </div>

            <!-- Ödünç Verme Tarihi -->
            <div>
                <label for="borrow_date" class="block text-sm font-medium text-gray-700 mb-1">Ödünç Verme Tarihi <span class="text-red-500">*</span></label>
                <input type="date" name="borrow_date" id="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Teslim Tarihi -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Teslim Tarihi <span class="text-red-500">*</span></label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+15 days'))) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">Varsayılan teslim süresi 15 gündür.</p>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('admin.borrowings.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                İptal
            </a>
            <button type="submit" id="submitButton" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" disabled>
                Ödünç Ver
            </button>
        </div>
    </form>
</div>

<script>
    let selectedBook = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Minimum tarihi bugün olarak ayarla
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('borrow_date').setAttribute('min', today);
        document.getElementById('due_date').setAttribute('min', today);
        
        // Ödünç verme tarihi değiştiğinde, teslim tarihini güncelle
        document.getElementById('borrow_date').addEventListener('change', function() {
            const borrowDate = new Date(this.value);
            const dueDate = new Date(borrowDate);
            dueDate.setDate(dueDate.getDate() + 15); // 15 gün ekle
            
            const formattedDueDate = dueDate.toISOString().split('T')[0];
            document.getElementById('due_date').value = formattedDueDate;
        });

        // Enter tuşuna basıldığında arama yapma
        document.getElementById('bookSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBook();
            }
        });

        checkFormValidity();
        
        // Form alanları değiştiğinde kontrol et
        document.getElementById('user_id').addEventListener('change', checkFormValidity);
    });

    async function searchBook() {
        const searchTerm = document.getElementById('bookSearch').value;
        if (!searchTerm) return;

        const resultsDiv = document.getElementById('bookSearchResults');
        const resultContent = document.getElementById('bookResult');
        resultContent.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div><p class="mt-2 text-sm text-gray-500">Aranıyor...</p></div>';
        resultsDiv.classList.remove('hidden');

        try {
            const response = await fetch(`/admin/books/borrowing-search?query=${encodeURIComponent(searchTerm)}`);
            const data = await response.json();

            if (data.books && data.books.length > 0) {
                let booksHtml = '<div class="space-y-2">';
                
                data.books.forEach(book => {
                    // Kitabın ödünç verilip verilemeyeceğini kontrol et
                    const isAvailable = !data.borrowedBookIds.includes(book.id);
                    const bookClass = isAvailable ? 'border-green-200 hover:bg-green-50' : 'border-red-200 bg-red-50 cursor-not-allowed';
                    const authorsList = book.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ');
                    
                    booksHtml += `
                        <div class="border ${bookClass} rounded p-3 flex justify-between items-center">
                            <div>
                                <p class="font-medium">${book.name || book.title}</p>
                                <p class="text-sm text-gray-600">Yazar: ${authorsList}</p>
                                <p class="text-sm text-gray-600">ISBN: ${book.isbn || 'Belirtilmemiş'}</p>
                                <p class="text-xs ${isAvailable ? 'text-green-600' : 'text-red-600'}">
                                    ${isAvailable ? 'Mevcut' : 'Bu kitap şu anda ödünç verilmiş'}
                                </p>
                            </div>
                            ${isAvailable ? `<button type="button" onclick='selectBook(${JSON.stringify(book)})' class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Seç</button>` : ''}
                        </div>
                    `;
                });
                
                booksHtml += '</div>';
                resultContent.innerHTML = booksHtml;
            } else {
                resultContent.innerHTML = '<p class="text-red-500 text-center py-4">Kitap bulunamadı</p>';
            }
        } catch (error) {
            resultContent.innerHTML = '<p class="text-red-500 text-center py-4">Arama sırasında bir hata oluştu</p>';
        }
    }

    function selectBook(book) {
        selectedBook = book;
        document.getElementById('selected_book_id').value = book.id;
        document.getElementById('bookSearchResults').classList.add('hidden');
        
        // Seçilen kitap bilgilerini göster
        const selectedBookDetails = document.getElementById('selectedBookDetails');
        const authorsList = book.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ');
        selectedBookDetails.innerHTML = `
            <p><strong>Kitap Adı:</strong> ${book.name || book.title}</p>
            <p><strong>ISBN:</strong> ${book.isbn || 'Belirtilmemiş'}</p>
            <p><strong>Yazar:</strong> ${authorsList}</p>
        `;
        document.getElementById('selectedBookInfo').classList.remove('hidden');
        
        checkFormValidity();
    }

    function checkFormValidity() {
        const user = document.getElementById('user_id').value;
        const submitBtn = document.getElementById('submitButton');
        
        if (user && selectedBook) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
</script>
@endsection 
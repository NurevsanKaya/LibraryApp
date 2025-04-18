@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Yeni Ödünç İşlemi</h1>
        <p class="text-gray-600 mt-1">Kullanıcıya birden fazla kitap ödünç verebilirsiniz</p>
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
        
        <div class="mb-6">
            <!-- Kullanıcı Seçimi -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Kullanıcı <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <select name="user_id" id="user_id" required class="select2-tailwind flex-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Kullanıcı Seçin</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div id="booksContainer">
            <!-- İlk kitap girişi -->
            <div class="book-entry border rounded-md p-4 mb-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-medium">Kitap #1</h3>
                    <button type="button" class="text-red-500 hover:text-red-700 disabled:opacity-50 remove-book" disabled>
                        <i class="fas fa-trash-alt"></i> Kaldır
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Kitap Arama Bölümü -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kitap Barkodu ile Ara <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="text" class="bookSearch flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Kitap barkodunu girin...">
                            <button type="button" class="searchBookBtn bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                <i class="fas fa-search mr-2"></i> Ara
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Lütfen kitabın barkodunu girin</p>
                        <input type="hidden" name="stock_ids[]" class="selected-stock-id">
                    </div>

                    <!-- Ödünç Süreleri -->
                    <div>
                        <label for="borrow_durations_0" class="block text-sm font-medium text-gray-700 mb-1">Ödünç Süresi (Gün) <span class="text-red-500">*</span></label>
                        <input type="number" name="borrow_durations[]" id="borrow_durations_0" value="15" min="1" max="365" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Varsayılan: 15 gün</p>
                    </div>
                    
                    <!-- Kitap Arama Sonuçları -->
                    <div class="bookSearchResults md:col-span-2 mb-4 hidden">
                        <div class="border rounded-md p-4">
                            <h4 class="font-medium mb-2">Bulunan Kitaplar:</h4>
                            <div class="bookResult max-h-64 overflow-y-auto"></div>
                        </div>
                    </div>

                    <!-- Seçilen Kitap Bilgileri -->
                    <div class="selectedBookInfo md:col-span-2 mb-4 hidden">
                        <div class="border rounded-md p-4 bg-blue-50">
                            <h4 class="font-medium mb-2 text-blue-800">Seçilen Kitap:</h4>
                            <div class="selectedBookDetails text-sm text-blue-600"></div>
                        </div>
                    </div>

                    <!-- Tarih Hesaplaması -->
                    <div class="md:col-span-2 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ödünç Verme Tarihi <span class="text-red-500">*</span></label>
                            <input type="date" name="borrow_dates[]" value="{{ old('borrow_date', date('Y-m-d')) }}" required
                                class="borrow-date w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teslim Tarihi</label>
                            <input type="date" name="due_dates[]" value="{{ old('due_date', date('Y-m-d', strtotime('+15 days'))) }}" required
                                class="due-date w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mb-6">
            <button type="button" id="addBookButton" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                <i class="fas fa-plus mr-2"></i> Kitap Ekle
            </button>
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
    let bookTemplate = null;
    let bookCounter = 1;
    let selectedBooks = [null]; // İlk kitap için null değeri
    let selectedStocks = [null]; // Seçilen stoklar için

    $(document).ready(function() {
        // Select2'yi başlat
        $('#user_id').select2({
            placeholder: 'Kullanıcı ara...',
            allowClear: true,
            width: '100%',
            dropdownCssClass: 'select2-dropdown-custom',
            ajax: {
                url: '{{ route("admin.borrowings.search.users") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            language: {
                noResults: function() {
                    return "Kullanıcı bulunamadı";
                },
                searching: function() {
                    return "Aranıyor...";
                },
                inputTooShort: function() {
                    return "En az 2 karakter girin";
                }
            },
            minimumInputLength: 2 // Bu parametre minimum 2 karakter girilmesini zorunlu tutar.
        });

        // Select2 CSS ayarlarını düzenle
        $('.select2-container--default .select2-selection--single').css({
            'height': '42px',
            'padding': '6px 12px',
            'border-radius': '0.375rem',
            'border-color': 'rgb(209, 213, 219)',
            'display': 'flex',
            'align-items': 'center'
        });

        $('.select2-container--default .select2-selection--single .select2-selection__arrow').css({
            'height': '40px'
        });

        // Kitap şablonunu kaydet
        bookTemplate = $('.book-entry').first().clone();
        
        // Minimum tarihi bugün olarak ayarla
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('.borrow-date').forEach(el => {
            el.setAttribute('min', today);
        });
        document.querySelectorAll('.due-date').forEach(el => {
            el.setAttribute('min', today);
        });
        
        // Ödünç verme tarihi değiştiğinde, teslim tarihini güncelle
        $(document).on('change', '.borrow-date', function() {
            const durationInput = $(this).closest('.book-entry').find('input[name="borrow_durations[]"]');
            const dueDate = $(this).closest('.book-entry').find('.due-date');
            
            const borrowDate = new Date(this.value);
            const newDueDate = new Date(borrowDate);
            newDueDate.setDate(borrowDate.getDate() + parseInt(durationInput.val() || 15));
            
            const formattedDueDate = newDueDate.toISOString().split('T')[0];
            dueDate.val(formattedDueDate);
        });
        
        // Süre değiştiğinde teslim tarihini güncelle
        $(document).on('change', 'input[name="borrow_durations[]"]', function() {
            const borrowDateInput = $(this).closest('.book-entry').find('.borrow-date');
            const dueDate = $(this).closest('.book-entry').find('.due-date');
            
            const borrowDate = new Date(borrowDateInput.val());
            const newDueDate = new Date(borrowDate);
            newDueDate.setDate(borrowDate.getDate() + parseInt(this.value || 15));
            
            const formattedDueDate = newDueDate.toISOString().split('T')[0];
            dueDate.val(formattedDueDate);
        });

        // Kitap ara butonlarına tıklama
        $(document).on('click', '.searchBookBtn', function() {
            const bookEntry = $(this).closest('.book-entry');
            searchBook(bookEntry);
        });
        
        // Enter tuşuna basıldığında arama yapma
        $(document).on('keypress', '.bookSearch', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const bookEntry = $(this).closest('.book-entry');
                searchBook(bookEntry);
            }
        });
        
        // Kitap kaldır butonlarına tıklama
        $(document).on('click', '.remove-book', function() {
            const bookEntry = $(this).closest('.book-entry');
            const index = $('.book-entry').index(bookEntry);
            
            selectedBooks.splice(index, 1);
            selectedStocks.splice(index, 1);
            bookEntry.remove();
            
            // Kitap numaralarını güncelle
            $('.book-entry').each(function(i) {
                $(this).find('h3').text(`Kitap #${i+1}`);
            });
            
            // Eğer tek kitap kaldıysa, kaldır butonunu devre dışı bırak
            if ($('.book-entry').length === 1) {
                $('.remove-book').prop('disabled', true);
            }
            
            checkFormValidity();
        });

        // Select2 değiştiğinde form validasyonunu kontrol et
        $('#user_id').on('change', function() {
            checkFormValidity();
        });

        // Kitap ekle butonuna tıklama
        $('#addBookButton').on('click', function() {
            bookCounter++;
            const newBook = bookTemplate.clone();
            
            // ID ve etiketleri güncelle
            newBook.find('h3').text(`Kitap #${bookCounter}`);
            newBook.find('input[id^="borrow_durations_"]').attr('id', `borrow_durations_${bookCounter-1}`);
            
            // Seçilen kitap bilgilerini temizle
            newBook.find('.selectedBookInfo').addClass('hidden');
            newBook.find('.selectedBookDetails').empty();
            newBook.find('.bookSearch').val('');
            newBook.find('.selected-stock-id').val('');
            
            // Kaldır butonunu etkinleştir ve tüm kaldır butonlarını görünür yap
            newBook.find('.remove-book').prop('disabled', false);
            $('.remove-book').prop('disabled', false);
            
            // Container'a ekle
            $('#booksContainer').append(newBook);
            
            // selectedBooks dizisini güncelle
            selectedBooks.push(null);
            selectedStocks.push(null);
        });

        checkFormValidity();
    });

    async function searchBook(bookEntry) {
        const barcode = bookEntry.find('.bookSearch').val();
        if (!barcode) {
            alert('Lütfen bir barkod girin!');
            return;
        }

        const resultsDiv = bookEntry.find('.bookSearchResults');
        const resultContent = bookEntry.find('.bookResult');
        resultContent.html('<div class="text-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div><p class="mt-2 text-sm text-gray-500">Barkod aranıyor...</p></div>');
        resultsDiv.removeClass('hidden');

        try {
            const response = await fetch(`/admin/books/borrowing-search?query=${encodeURIComponent(barcode)}`);
            const data = await response.json();
            console.log('Search API response:', data); // Debug için API yanıtını konsola yazdır

            if (data.success && data.books && data.books.length > 0) {
                let booksHtml = '<div class="space-y-2">';
                
                // Başarı mesajı göster
                booksHtml += '<div class="bg-green-50 text-green-800 p-2 rounded mb-2 text-sm">Barkod ile kitap bulundu.</div>';
                
                data.books.forEach(book => {
                    // Kitabın kullanılabilir stokları
                    const availableStocks = book.stocks.filter(stock => stock.status === 'available');
                    
                    // Barkodla eşleşen stokları göster
                    availableStocks.forEach(stock => {
                        // Bu stok ID'si zaten seçilmiş mi kontrol et
                        const isAlreadySelected = selectedStocks.some(selectedStock => selectedStock && selectedStock.id === stock.id);
                        
                        const isSelectable = !isAlreadySelected;
                        
                        let statusClass = isSelectable ? 'text-green-600' : 'text-red-600';
                        let stockClass = isSelectable ? 'border-green-200 hover:bg-green-50' : 'border-red-200 bg-red-50 cursor-not-allowed';
                        let statusMessage = isAlreadySelected ? 'Bu kopya zaten seçilmiş' : 'Stokta mevcut';
                        
                        booksHtml += `
                            <div class="border ${stockClass} rounded p-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium">${book.name || book.title}</p>
                                    <p class="text-sm text-gray-600">Yazar: ${book.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ')}</p>
                                    <p class="text-sm text-gray-600">ISBN: ${book.isbn || 'Belirtilmemiş'}</p>
                                    <p class="text-sm font-medium">Barkod: ${stock.barcode || 'Belirtilmemiş'}</p>
                                    <p class="text-xs ${statusClass}">
                                        ${statusMessage}
                                    </p>
                                </div>
                                ${isSelectable ? `<button type="button" onclick='selectStock(${JSON.stringify(book).replace(/'/g, "\\'")}, ${JSON.stringify(stock).replace(/'/g, "\\'")}, ${$('.book-entry').index(bookEntry)})' class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Seç</button>` : ''}
                            </div>
                        `;
                    });
                });
                
                booksHtml += '</div>';
                resultContent.html(booksHtml);
            } else {
                // Hata mesajını göster
                resultContent.html(`<div class="bg-red-50 text-red-800 p-4 rounded text-center">
                    <p class="font-medium">Kitap bulunamadı</p>
                    <p class="text-sm mt-1">${data.message || 'Bu barkoda sahip kullanılabilir bir kitap bulunamadı.'}</p>
                    <p class="text-sm mt-2">Lütfen barkodu kontrol edip tekrar deneyin.</p>
                </div>`);
            }
        } catch (error) {
            console.error('Book search error:', error); // Hata durumunda konsola yazdır
            resultContent.html('<div class="bg-red-50 text-red-800 p-4 rounded text-center"><p class="font-medium">Arama sırasında bir hata oluştu</p><p class="text-sm mt-1">Lütfen daha sonra tekrar deneyin veya sistem yöneticisiyle iletişime geçin.</p></div>');
        }
    }

    function selectStock(book, stock, index) {
        // Seçilen kitap ve stok bilgilerini kaydet
        selectedBooks[index] = book;
        selectedStocks[index] = stock;
        
        // DOM elementlerini güncelle
        const bookEntry = $('.book-entry').eq(index);
        bookEntry.find('.selected-stock-id').val(stock.id);
        bookEntry.find('.bookSearchResults').addClass('hidden');
        
        const selectedBookDetails = bookEntry.find('.selectedBookDetails');
        const authorsList = book.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ');
        selectedBookDetails.html(`
            <p><strong>Kitap Adı:</strong> ${book.name || book.title}</p>
            <p><strong>ISBN:</strong> ${book.isbn || 'Belirtilmemiş'}</p>
            <p><strong>Yazar:</strong> ${authorsList}</p>
            <p><strong>Barkod:</strong> ${stock.barcode || 'Belirtilmemiş'}</p>
        `);
        bookEntry.find('.selectedBookInfo').removeClass('hidden');
        
        checkFormValidity();
    }

    function checkFormValidity() {
        const user = document.getElementById('user_id').value;
        const submitBtn = document.getElementById('submitButton');
        
        // En az bir kitap seçilmiş mi kontrol et
        const hasSelectedBook = selectedStocks.some(stock => stock !== null);
        
        if (user && hasSelectedBook) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }
</script>
@endsection 
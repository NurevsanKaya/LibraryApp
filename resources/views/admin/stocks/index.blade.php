@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Stok Yönetimi</h1>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                    onclick="showModal('add')">
                <i class="fas fa-plus mr-2"></i> Yeni Stok Ekle
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="stockSearch" placeholder="Barkod ile ara..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-4">
                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tüm Durumlar</option>
                    <option value="available">Rafta Mevcut</option>
                    <option value="borrowed">Ödünç Verilmiş</option>
                </select>
                <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md" onclick="filterStocks()">
                    <i class="fas fa-filter mr-2"></i> Filtrele
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.remove()">
                        <title>Kapat</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Stocks Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barkod</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kitap</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Raf</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Edinme Kaynağı</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Edinme Tarihi</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Durum</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $stock->barcode }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $stock->book->name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $stock->shelf->shelf_number }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $stock->acquisition_source }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $stock->acquisition_date }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <span class="px-2 py-1 text-xs rounded-full {{ $stock->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $stock->status === 'available' ? 'Rafta Mevcut' : 'Ödünç Verilmiş' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="flex space-x-2">
                                    <button class="text-blue-500 hover:text-blue-700" onclick="showModal('edit', {{ $stock->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="post" action="{{ route('admin.stocks.destroy', $stock->id) }}" class="inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Bu stoğu silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <button class="text-gray-500 hover:text-gray-700" onclick="showModal('view', {{ $stock->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-4 border-b border-gray-200 text-center text-gray-500">
                                Stok bulunamadı
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $stocks->links() }}
        </div>
    </div>

    <!-- Stock Modal -->
    <div id="stockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">Yeni Stok Ekle</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="hideModal()">
                    <span class="sr-only">Kapat</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Loading ekledik -->
            <div id="loadingSpinner" class="hidden flex justify-center items-center p-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>

            <form id="stockForm" method="POST" action="{{ route('admin.stocks.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="book_id" id="selected_book_id">

                <!-- Kitap Arama Bölümü -->
                <div class="mb-4" id="bookSearchSection">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kitap Ara (ISBN)</label>
                    <div class="flex gap-2">
                        <input type="text" id="isbnSearch" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="ISBN numarası...">
                        <button type="button" onclick="searchBook()" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            <i class="fas fa-search mr-2"></i> Ara
                        </button>
                    </div>
                </div>

                <!-- Kitap Arama Sonuçları -->
                <div id="bookSearchResults" class="mb-4 hidden">
                    <div class="border rounded-md p-4">
                        <h4 class="font-medium mb-2">Bulunan Kitap:</h4>
                        <div id="bookResult" class="text-sm"></div>
                        <button type="button" id="selectBookButton" onclick="selectBook()" class="mt-2 bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600">
                            Seç
                        </button>
                    </div>
                </div>

                <!-- Seçilen Kitap Bilgileri -->
                <div id="selectedBookInfo" class="mb-4 hidden">
                    <div class="border rounded-md p-4 bg-blue-50">
                        <h4 class="font-medium mb-2 text-blue-800">Seçilen Kitap:</h4>
                        <div id="selectedBookDetails" class="text-sm text-blue-600"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Barkod -->
                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barkod</label>
                        <div class="flex gap-2">
                            <input type="text" name="barcode" id="barcode" required readonly
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            <button type="button" onclick="generateBarcode()" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Raf -->
                    <div>
                        <label for="shelf_id" class="block text-sm font-medium text-gray-700 mb-1">Raf</label>
                        <div class="relative">
                            <select name="shelf_id" id="shelf_id" required disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                <option value="">Önce kitap seçin</option>
                            </select>
                            <div id="shelfMessage" class="mt-1 text-sm text-gray-500"></div>
                        </div>
                    </div>

                   
                    <!-- Edinme Kaynağı -->
                    <div>
                    <label for="acquisition_source_id" class="block text-sm font-medium text-gray-700 mb-1">Edinme Kaynağı</label>
                        <select name="acquisition_source_id" id="acquisition_source_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 select2-tailwind"
                            required>
                            <option value="">Elde Edinme Kaynağı Seçin</option>
                            @foreach($acquisitionSources as $as)
                                <option value="{{ $as->id }}">
                                    {{ $as->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Edinme Fiyatı -->
                    <div>
                        <label for="acquisition_price" class="block text-sm font-medium text-gray-700 mb-1">Edinme Fiyatı</label>
                        <input type="number" step="0.01" name="acquisition_price" id="acquisition_price"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Edinme Tarihi -->
                    <div>
                        <label for="acquisition_date" class="block text-sm font-medium text-gray-700 mb-1">Edinme Tarihi</label>
                        <input type="date" name="acquisition_date" id="acquisition_date"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Durum -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                        <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="available">Rafta Mevcut</option>
                            <option value="borrowed">Ödünç Verilmiş</option>
                        </select>
                    </div>

                
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        İptal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedBook = null;

        function showModal(mode, stockId = null) {
            document.getElementById('stockModal').classList.remove('hidden');
            const modalTitle = document.getElementById('modal-title');
            const form = document.getElementById('stockForm');
            const bookSearchSection = document.getElementById('bookSearchSection');
            const shelfSelect = document.getElementById('shelf_id');
            
            if (mode === 'add') {
                modalTitle.textContent = 'Yeni Stok Ekle';
                form.reset();
                form.action = "{{ route('admin.stocks.store') }}";
                document.getElementById('form-method').value = 'POST';
                bookSearchSection.classList.remove('hidden');
                shelfSelect.disabled = true;
                shelfSelect.innerHTML = '<option value="">Önce kitap seçin</option>';
                document.getElementById('shelfMessage').textContent = '';
                generateBarcode();
            } else if (mode === 'edit') {
                modalTitle.textContent = 'Stok Düzenle';
                form.action = `/admin/stocks/${stockId}`;
                document.getElementById('form-method').value = 'PUT';
                bookSearchSection.classList.add('hidden');
                loadStockData(stockId);
            } else if (mode === 'view') {
                modalTitle.textContent = 'Stok Detayları';
                loadStockData(stockId);
                // Form elemanlarını readonly yap
                Array.from(form.elements).forEach(element => {
                    element.disabled = true;
                });
            }
        }

        function hideModal() {
            document.getElementById('stockModal').classList.add('hidden');
            document.getElementById('stockForm').reset();
            document.getElementById('bookSearchResults').classList.add('hidden');
            document.getElementById('selectedBookInfo').classList.add('hidden');
            document.getElementById('shelf_id').disabled = true;
            document.getElementById('shelfMessage').textContent = '';
            selectedBook = null;
            // Form elemanlarını tekrar aktif et
            Array.from(document.getElementById('stockForm').elements).forEach(element => {
                element.disabled = false;
            });
        }

        async function searchBook() {
            const isbn = document.getElementById('isbnSearch').value;
            if (!isbn) {
                alert('Lütfen bir ISBN numarası girin');
                return;
            }

            const spinner = document.getElementById('loadingSpinner');
            const resultsDiv = document.getElementById('bookSearchResults');
            const resultContent = document.getElementById('bookResult');

            spinner.classList.remove('hidden');
            resultsDiv.classList.add('hidden');

            try {
                console.log('Arama yapılıyor:', isbn);
                const response = await fetch(`/admin/books/stock-search?isbn=${encodeURIComponent(isbn.trim())}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP hata! durum: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Sunucu yanıtı:', data);

                if (data.book) {
                    selectedBook = data.book;
                    resultContent.innerHTML = `
                        <p><strong>Kitap Adı:</strong> ${data.book.name}</p>
                        <p><strong>ISBN:</strong> ${data.book.isbn}</p>
                        <p><strong>Yazar:</strong> ${data.book.authors ? data.book.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ') : 'Belirtilmemiş'}</p>
                    `;
                    resultsDiv.classList.remove('hidden');
                    document.getElementById('selectBookButton').classList.remove('hidden');
                } else {
                    console.log('Kitap bulunamadı. Aranan ISBN:', isbn);
                    resultContent.innerHTML = `
                        <p class="text-red-500">Bu ISBN numarasına (${isbn}) sahip kitap bulunamadı.</p>
                        <p class="text-sm text-gray-500">Lütfen ISBN numarasını kontrol edin ve tekrar deneyin.</p>
                    `;
                    resultsDiv.classList.remove('hidden');
                    document.getElementById('selectBookButton').classList.add('hidden');
                }
            } catch (error) {
                console.error('Arama hatası:', error);
                resultContent.innerHTML = `
                    <p class="text-red-500">Arama sırasında bir hata oluştu:</p>
                    <p class="text-sm text-gray-500">${error.message}</p>
                `;
                resultsDiv.classList.remove('hidden');
                document.getElementById('selectBookButton').classList.add('hidden');
            } finally {
                spinner.classList.add('hidden');
            }
        }

        async function selectBook() {
            if (selectedBook) {
                document.getElementById('selected_book_id').value = selectedBook.id;
                document.getElementById('bookSearchSection').classList.add('hidden');
                document.getElementById('bookSearchResults').classList.add('hidden');
                
                // Seçilen kitap bilgilerini göster
                const selectedBookDetails = document.getElementById('selectedBookDetails');
                selectedBookDetails.innerHTML = `
                    <p><strong>Kitap Adı:</strong> ${selectedBook.name}</p>
                    <p><strong>ISBN:</strong> ${selectedBook.isbn}</p>
                    <p><strong>Yazar:</strong> ${selectedBook.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ')}</p>
                `;
                document.getElementById('selectedBookInfo').classList.remove('hidden');

                // Uygun rafları getir
                await loadAvailableShelves(selectedBook.id);
            }
        }

        async function loadAvailableShelves(bookId) {
            try {
                const response = await fetch(`/admin/stocks/available-shelves?book_id=${bookId}`);//path ile parametre yollamak istersek ? ile bağlayabiliriz    
                const data = await response.json();
                
                const shelfSelect = document.getElementById('shelf_id');
                const shelfMessage = document.getElementById('shelfMessage');
                
                // Raf seçimini aktif et
                shelfSelect.disabled = false;
                shelfSelect.innerHTML = '<option value="">Raf Seçin</option>';
                
                // Rafları listele
                data.shelves.forEach(shelf => {
                    const option = document.createElement('option');
                    option.value = shelf.id;
                    option.textContent = `${shelf.shelf_number} (${shelf.stock_count}/10 kitap)`;
                    shelfSelect.appendChild(option);
                });

                // Mesajı göster
                shelfMessage.textContent = data.message;
                shelfMessage.className = 'mt-1 text-sm ' + 
                    (data.message.includes('Aynı kitap') ? 'text-green-600' : 'text-blue-600');

                // Eğer tek raf varsa ve aynı ISBN'li kitapsa otomatik seç
                if (data.shelves.length === 1 && data.message.includes('Aynı kitap')) {
                    shelfSelect.value = data.shelves[0].id;
                }
            } catch (error) {
                console.error('Raflar yüklenirken hata:', error);
            }
        }
        
        async function loadStockData(stockId) {
            const spinner = document.getElementById('loadingSpinner');
            const form = document.getElementById('stockForm');
            
            // Yükleme başladığında
            spinner.classList.remove('hidden');
            form.classList.add('hidden');

            try {
                const response = await fetch(`/admin/stocks/${stockId}`);
                const data = await response.json();

                if (data.stock) {
                    const stock = data.stock;
                    
                    // Form alanlarını doldur
                    document.getElementById('barcode').value = stock.barcode;
                    document.getElementById('acquisition_source_id').value = stock.acquisition_source;
                    document.getElementById('acquisition_price').value = stock.acquisition_price;
                    document.getElementById('acquisition_date').value = stock.acquisition_date;
                    document.getElementById('selected_book_id').value = stock.book_id;
                    document.getElementById('status').value = stock.status;

                    // Raf seçimini güncelle
                    const shelfSelect = document.getElementById('shelf_id');
                    shelfSelect.disabled = false;
                    
                    // Mevcut rafı seç
                    if (stock.shelf) {
                        await loadAvailableShelves(stock.book_id);
                        shelfSelect.value = stock.shelf_id;
                    }
                    
                    // Seçilen kitap bilgilerini göster
                    if (stock.book) {
                        const selectedBookDetails = document.getElementById('selectedBookDetails');
                        selectedBookDetails.innerHTML = `
                            <p><strong>Kitap Adı:</strong> ${stock.book.name}</p>
                            <p><strong>ISBN:</strong> ${stock.book.isbn}</p>
                            <p><strong>Yazar:</strong> ${stock.book.authors ? stock.book.authors.map(a => `${a.first_name} ${a.last_name}`).join(', ') : ''}</p>
                        `;
                        document.getElementById('selectedBookInfo').classList.remove('hidden');
                        document.getElementById('bookSearchSection').classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Stok verisi yüklenirken hata:', error);
            } finally {
                // Yükleme tamamlandığında
                spinner.classList.add('hidden');
                form.classList.remove('hidden');
            }
        }

        function generateBarcode() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const random = String(Math.floor(Math.random() * 10000)).padStart(4, '0');
            
            const barcode = `${year}${month}${day}${random}`;
            document.getElementById('barcode').value = barcode;
        }

        function filterStocks() {
            // Seçili durumu al
            const durum = document.getElementById('statusFilter').value;
            
            // Arama metnini al
            const barkod = document.getElementById('stockSearch').value;
            
            // Yeni URL oluştur
            let yeniURL = '/admin/stocks';
            
            // Parametreleri ekle
            let parametreler = [];
            
            if (durum) {
                parametreler.push('status=' + durum);
            }
            
            if (barkod) {
                parametreler.push('search=' + barkod);
            }
            
            // Parametreler varsa URL'ye ekle
            if (parametreler.length > 0) {
                yeniURL = yeniURL + '?' + parametreler.join('&');
            }
            
            // Sayfayı yenile
            window.location.href = yeniURL;
        }

        // Sayfa yüklendiğinde önceki filtreleri seç
        document.addEventListener('DOMContentLoaded', function() {
            // URL'den parametreleri al
            const urlParams = new URLSearchParams(window.location.search);
            
            // Durum filtresini ayarla
            const durum = urlParams.get('status');
            if (durum) {
                document.getElementById('statusFilter').value = durum;
            }
            
            // Barkod filtresini ayarla
            const barkod = urlParams.get('search');
            if (barkod) {
                document.getElementById('stockSearch').value = barkod;
            }
        });
    </script>
@endsection
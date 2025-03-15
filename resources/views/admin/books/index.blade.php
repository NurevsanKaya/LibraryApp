@extends('layouts.admin')

@section('content')
    <style>
        /* Yayınevi için Select2 özel stil */
        #publisher_id + .select2-container .select2-selection--single {
            height: 42px !important;
            padding: 0.375rem 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            border-radius: 0.375rem !important; /* rounded-md */
        }
        
        #publisher_id + .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
            padding-left: 0 !important;
            color: #374151 !important; /* text-gray-700 */
        }
        
        #publisher_id + .select2-container .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
        }
        
        #publisher_id + .select2-container .select2-selection--single .select2-selection__placeholder {
            color: #6b7280 !important; /* text-gray-500 */
        }
        
        /* Yazarlar için Select2 özel stil */
        #authors_select + .select2-container .select2-selection--multiple {
            min-height: 42px !important;
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            border-radius: 0.375rem !important; /* rounded-md */
            padding: 0.375rem 0.75rem !important;
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
        }
        
        #authors_select + .select2-container .select2-selection--multiple .select2-selection__rendered {
            display: inline-flex !important;
            flex-wrap: wrap !important;
            padding-left: 0 !important;
            gap: 0.25rem !important;
        }
        
        #authors_select + .select2-container .select2-selection--multiple .select2-selection__choice {
            background-color: #e5e7eb !important; /* bg-gray-200 */
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            border-radius: 0.25rem !important; /* rounded */
            padding: 0.25rem 0.5rem !important;
            margin-top: 0.25rem !important;
            margin-right: 0.25rem !important;
            color: #374151 !important; /* text-gray-700 */
            font-size: 0.875rem !important; /* text-sm */
        }
        
        #authors_select + .select2-container .select2-selection--multiple .select2-selection__choice__remove {
            color:rgb(30, 71, 154) !important; /* text-gray-500 */
            margin-right: 0.25rem !important;
            border-right: none !important;
        }
        
        #authors_select + .select2-container .select2-selection--multiple .select2-selection__choice__remove:hover {
            background-color: transparent !important;
            color: #1f2937 !important; /* text-gray-800 */
        }
        
        #authors_select + .select2-container .select2-search--inline .select2-search__field {
            margin-top: 0 !important;
            height: 28px !important;
            font-size: 0.875rem !important; /* text-sm */
            color: #374151 !important; /* text-gray-700 */
        }
        
        /* Select2 dropdown stil */
        .select2-dropdown {
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            border-radius: 0.375rem !important; /* rounded-md */
        }
        
        .select2-search__field {
            border: 1px solid #d1d5db !important; /* border-gray-300 */
            border-radius: 0.25rem !important; /* rounded */
            padding: 0.375rem 0.75rem !important;
        }
        
        .select2-search__field:focus {
            outline: none !important;
            border-color: #3b82f6 !important; /* focus:border-blue-500 */
            box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5) !important; /* focus:ring-blue-500 */
        }
        
        .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.875rem !important; /* text-sm */
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important; /* bg-blue-500 */
            color: white !important;
        }
    </style>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Kitap Yönetimi</h1>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                    onclick="showModal('add')">
                <i class="fas fa-plus mr-2"></i> Yeni Kitap Ekle
            </button>
        </div>

        <!-- Arama ve filtreleme kısmı -->
        <div class="mb-6 flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Kitap ara..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-4">
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Kategoriler</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <button class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md">
                    <i class="fas fa-filter mr-2"></i> Filtrele
                </button>
            </div>
        </div>

        <!-- Başarı mesajı -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <title>Kapat</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        <!-- Kitapların gösterildiği tablo -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İsim</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ISBN</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Yazar</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Durum</th>
                        <th class="py-3 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">İçerik Yönetimi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->id }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->isbn }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                            @if($book->authors->count() > 0)
                                {{ $book->authors->map(function($author) {
                                    return $author->fullName();
                                })->join(', ') }}
                            @else
                            -
                            @endif
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">{{ $book->category->name ?? '-' }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Mevcut</span>
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                <div class="flex space-x-2">
                                   <button class="text-blue-500 hover:text-blue-700" onclick="showModal('edit', {{ $book->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                   <!--  <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>-->

                                    <!-- Sil Butonu -->
                                    <form method="post" action="{{ route('Book.destroy', $book->id) }}" style="margin: 0;">
                                        @csrf
                                        @method('delete')
                                        <button class="text-red-500 hover:text-red-700 ml-2" onclick="return confirm('Bu kitabı silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <button class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-3 px-4 border-b border-gray-200 text-center text-gray-500">
                                Kitap bulunamadı
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Sayfalama için gerekli-->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    </div>

    <!-- Book Modal -->
    <div id="bookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                    Yeni Kitap Ekle
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="hideModal()">
                    <span class="sr-only">Kapat</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- modalı açarken var yüklenme şeysi -->
            <div id="loadingSpinner" class="hidden flex justify-center items-center p-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>

            <form id="bookForm" method="POST" action="{{ route('admin.books.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Kitap Adı -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Kitap Adı</label>
                        <input type="text" name="name" id="name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <input type="text" name="isbn" id="isbn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <!-- Yayın Yılı -->
                    <div>
                        <label for="publication_year" class="block text-sm font-medium text-gray-700 mb-1">Yayın Yılı</label>
                        <input type="number" name="publication_year" id="publication_year"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <!-- Yayınevi -->
                    <div>
                        <label for="publisher_id" class="block text-sm font-medium text-gray-700 mb-1">Yayınevi</label>
                        <select name="publisher_id" id="publisher_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 select2-tailwind"
                            required>
                            <option value="">Yayınevi Seçin</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}">
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" id="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Kategori Seçin</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tür -->
                    <div>
                        <label for="genres_id" class="block text-sm font-medium text-gray-700 mb-1">Tür</label>
                        <select name="genres_id" id="genres_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">Tür Seçin</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->id }}">
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Yazarlar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Yazarlar</label>
                        <select name="authors[]" id="authors_select" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 select2-tailwind" 
                            multiple>
                            <option></option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->fullName() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Boş alan - dengeleme için -->
                    <div></div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300" onclick="hideModal()">
                        İptal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600" id="saveButton">
                        Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal işlemleri için JavaScript
        function showModal(mode, bookId = null) {
            const modal = document.getElementById('bookModal');
            const modalTitle = document.getElementById('modal-title');
            const form = document.getElementById('bookForm');
            const methodField = document.getElementById('form-method');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const saveButton = document.getElementById('saveButton');

            // Form sıfırlama
            form.reset();

            if (mode === 'add') {
                modalTitle.textContent = 'Yeni Kitap Ekle';
                form.action = "{{ route('admin.books.store') }}";
                methodField.value = 'POST';
                saveButton.textContent = 'Kaydet';

                // Modalı göster
                modal.classList.remove('hidden');
                
                // Select2'yi yeniden başlat
                initSelect2();
            }
            else if (mode === 'edit' && bookId) {
                modalTitle.textContent = 'Kitap Düzenle';
                form.action = `/admin/books/${bookId}`;
                methodField.value = 'PUT';
                saveButton.textContent = 'Güncelle';

                // Yükleniyor göster
                loadingSpinner.classList.remove('hidden');
                document.getElementById('bookForm').classList.add('hidden');

                // Modalı göster
                modal.classList.remove('hidden');

                // AJAX ile kitap verilerini çek
                fetch(`/admin/books/${bookId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Form alanlarını doldur
                        document.getElementById('name').value = data.book.name;
                        document.getElementById('isbn').value = data.book.isbn;
                        document.getElementById('publication_year').value = data.book.publication_year;
                        document.getElementById('publisher_id').value = data.book.publisher_id;
                        document.getElementById('category_id').value = data.book.category_id;
                        document.getElementById('genres_id').value = data.book.genres_id;

                        // Yazarları seç
                        const authorIds = data.authorIds;
                        if ($('#authors_select').length) {
                            $('#authors_select').val(authorIds).trigger('change');
                        } else {
                            // Eski checkbox sistemi için
                            document.querySelectorAll('input[name="authors[]"]').forEach(checkbox => {
                                checkbox.checked = authorIds.includes(parseInt(checkbox.value));
                            });
                        }

                        // Yükleniyor gizle
                        loadingSpinner.classList.add('hidden');
                        document.getElementById('bookForm').classList.remove('hidden');
                        
                        // Select2'yi yeniden başlat
                        initSelect2();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        hideModal();
                        alert('Kitap bilgileri yüklenirken bir hata oluştu.');
                    });
            }
        }

        function hideModal() {
            const modal = document.getElementById('bookModal');
            modal.classList.add('hidden');
        }
        
        // Select2'yi başlat
        function initSelect2() {
            // Yazarlar için Select2
            $('#authors_select').select2({
                placeholder: "Yazar seçin veya arayın...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#bookModal'),
                language: {
                    noResults: function() {
                        return "Sonuç bulunamadı";
                    },
                    searching: function() {
                        return "Aranıyor...";
                    }
                },
                // Tailwind CSS sınıflarını ekle
                templateSelection: formatSelection,
                templateResult: formatResult
            });
            
            // Yayınevi için Select2
            $('#publisher_id').select2({
                placeholder: "Yayınevi seçin veya arayın...",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#bookModal'),
                language: {
                    noResults: function() {
                        return "Sonuç bulunamadı";
                    },
                    searching: function() {
                        return "Aranıyor...";
                    }
                }
            });
            
            // Select2 dropdown için genel stil
            setTimeout(function() {
                // Dropdown ve arama alanı
                $('.select2-dropdown').addClass('border border-gray-300 rounded-md');
                $('.select2-search__field').addClass('border border-gray-300 rounded focus:outline-none focus:ring-blue-500 focus:border-blue-500');
            }, 100);
        }
        
        // Seçilen öğelerin formatı
        function formatSelection(author) {
            if (!author.id) return author.text;
            return $('<span class="inline-flex items-center px-2 py-1 bg-gray-200 text-gray-800 text-sm rounded">' + 
                     '<span>' + author.text + '</span>' +
                     '</span>');
        }
        
        // Sonuçların formatı
        function formatResult(author) {
            if (!author.id) return author.text;
            return $('<div class="py-2 px-3 text-sm hover:bg-blue-100 flex items-center">' + 
                     '<span>' + author.text + '</span>' +
                     '</div>');
        }
        
        // Sayfa yüklendiğinde Select2'yi başlat
        $(document).ready(function() {
            initSelect2();
        });
    </script>
@endsection

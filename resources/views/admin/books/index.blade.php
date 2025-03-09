@extends('layouts.admin')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Kitap Yönetimi</h1>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
                    onclick="showModal('add')">
                <i class="fas fa-plus mr-2"></i> Yeni Kitap Ekle
            </button>
        </div>

        <!-- Search and Filter -->
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

        <!-- Success Message -->
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

        <!-- Books Table -->
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
                                    <!--<button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>-->

                                    <!-- Sil Butonu -->
                                    <form method="post" action="{{ route('Book.destroy', $book->id) }}" style="margin: 0;">
                                        @csrf
                                        @method('delete')
                                        <button class="text-red-500 hover:text-red-700 ml-2" onclick="return confirm('Bu ilanı silmek istediğinize emin misiniz?')">
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

        <!-- Pagination -->
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

            <!-- Loading Spinner -->
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Yazarlar</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($authors as $author)
                            <div class="flex items-center">
                                <input type="checkbox" name="authors[]" id="author_{{ $author->id }}" value="{{ $author->id }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="author_{{ $author->id }}" class="ml-2 text-sm text-gray-700">{{ $author->fullName() }}</label>
                            </div>
                        @endforeach
                    </div>
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

                        // Yazarları işaretle
                        const authorIds = data.authorIds;
                        document.querySelectorAll('input[name="authors[]"]').forEach(checkbox => {
                            checkbox.checked = authorIds.includes(parseInt(checkbox.value));
                        });

                        // Yükleniyor gizle
                        loadingSpinner.classList.add('hidden');
                        document.getElementById('bookForm').classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching book data:', error);
                        alert('Kitap verilerini getirirken bir hata oluştu.');
                        hideModal();
                    });
            }
        }

        function hideModal() {
            const modal = document.getElementById('bookModal');
            modal.classList.add('hidden');
        }
    </script>
@endsection

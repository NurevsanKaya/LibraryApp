
<div id="bookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" x-data="{ open: false }" x-show="open" x-on:keydown.escape.window="open = false">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                {{ isset($book) ? 'Kitap Düzenle' : 'Yeni Kitap Ekle' }}
            </h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" @click="open = false">
                <span class="sr-only">Kapat</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="bookForm" method="POST" action="{{ isset($book) ? route('admin.books.update', $book->id) : route('admin.books.store') }}">
            @csrf
            @if(isset($book))
                @method('PUT')
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Kitap Adı -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Kitap Adı</label>
                    <input type="text" name="name" id="name" value="{{ $book->name ?? old('name') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        required>
                </div>
                
                <!-- ISBN -->
                <div>
                    <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                    <input type="text" name="isbn" id="isbn" value="{{ $book->isbn ?? old('isbn') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        required>
                </div>
                
                <!-- Yayın Yılı -->
                <div>
                    <label for="publication_year" class="block text-sm font-medium text-gray-700 mb-1">Yayın Yılı</label>
                    <input type="number" name="publication_year" id="publication_year" value="{{ $book->publication_year ?? old('publication_year') }}" 
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
                            <option value="{{ $publisher->id }}" {{ (isset($book) && $book->publisher_id == $publisher->id) ? 'selected' : '' }}>
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
                            <option value="{{ $category->id }}" {{ (isset($book) && $book->category_id == $category->id) ? 'selected' : '' }}>
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
                            <option value="{{ $genre->id }}" {{ (isset($book) && $book->genres_id == $genre->id) ? 'selected' : '' }}>
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
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                {{ (isset($book) && $book->authors->contains($author->id)) ? 'checked' : '' }}>
                            <label for="author_{{ $author->id }}" class="ml-2 text-sm text-gray-700">{{ $author->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300" @click="open = false">
                    İptal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    {{ isset($book) ? 'Güncelle' : 'Kaydet' }}
                </button>
            </div>
        </form>
    </div>
</div>

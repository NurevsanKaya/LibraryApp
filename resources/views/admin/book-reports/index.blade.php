@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Kitap Raporları</h1>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <i class="fas fa-book text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Toplam Kitap</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalBooks }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 mr-4">
                    <i class="fas fa-hand-holding-heart text-orange-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Ödünçte Olan</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ $borrowedBooks }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <i class="fas fa-clock text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Bugün İade Edilecek</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $dueTodayBooks }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Filtre Butonları -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button type="button" data-filter="overdue" class="quick-filter px-4 py-2 bg-red-100 text-red-700 rounded-full hover:bg-red-200 flex items-center transition-all duration-200">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>Geciken Kitaplar</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
        <button type="button" data-filter="due_today" class="quick-filter px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 flex items-center transition-all duration-200">
            <i class="fas fa-clock mr-2"></i>
            <span>Bugün İade Edilecekler</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
        <button type="button" data-filter="most_borrowed" class="quick-filter px-4 py-2 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 flex items-center transition-all duration-200">
            <i class="fas fa-chart-line mr-2"></i>
            <span>En Çok Ödünç Alınanlar</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
        <button type="button" data-filter="added_last_month" class="quick-filter px-4 py-2 bg-green-100 text-green-700 rounded-full hover:bg-green-200 flex items-center transition-all duration-200">
            <i class="fas fa-calendar-plus mr-2"></i>
            <span>Son 1 Ayda Eklenenler</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
        <button type="button" data-filter="never_borrowed" class="quick-filter px-4 py-2 bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 flex items-center transition-all duration-200">
            <i class="fas fa-ban mr-2"></i>
            <span>Hiç Ödünç Alınmayanlar</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
        <button type="button" data-filter="available" class="quick-filter px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full hover:bg-emerald-200 flex items-center transition-all duration-200">
            <i class="fas fa-check-circle mr-2"></i>
            <span>Rafta Mevcut</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
        <button type="button" data-filter="active_borrowings" class="quick-filter px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full hover:bg-indigo-200 flex items-center transition-all duration-200">
            <i class="fas fa-sync-alt mr-2"></i>
            <span>Aktif Ödünç İşlemleri</span>
            <i class="fas fa-check ml-2 opacity-0"></i>
        </button>
    </div>

    <!-- Filtre Formu -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Temel Filtreler -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kitap Adı</label>
                <input type="text" name="name" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="isbn" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Yazar</label>
                <select name="author_id" class="w-full rounded-md border-gray-300">
                    <option value="">Seçiniz</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}">{{ $author->name }} {{ $author->surname }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Yayınevi</label>
                <select name="publisher_id" class="w-full rounded-md border-gray-300">
                    <option value="">Seçiniz</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Basım Yılı</label>
                <input type="number" name="publication_year" class="w-full rounded-md border-gray-300">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" class="w-full rounded-md border-gray-300">
                    <option value="">Seçiniz</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Durum Filtreleri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
                <select name="status" class="w-full rounded-md border-gray-300">
                    <option value="">Seçiniz</option>
                    <option value="available">Rafta</option>
                    <option value="borrowed">Ödünçte</option>
                    <option value="overdue">Gecikmiş</option>
                    <option value="reserved">Rezerve</option>
                </select>
            </div>

            <!-- Tarih Filtreleri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ödünç Alma Tarihi</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="borrow_date_start" class="w-full rounded-md border-gray-300" placeholder="Başlangıç">
                    <input type="date" name="borrow_date_end" class="w-full rounded-md border-gray-300" placeholder="Bitiş">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">İade Tarihi</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="return_date_start" class="w-full rounded-md border-gray-300" placeholder="Başlangıç">
                    <input type="date" name="return_date_end" class="w-full rounded-md border-gray-300" placeholder="Bitiş">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Edinme Tarihi</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="date" name="acquisition_date_start" class="w-full rounded-md border-gray-300" placeholder="Başlangıç">
                    <input type="date" name="acquisition_date_end" class="w-full rounded-md border-gray-300" placeholder="Bitiş">
                </div>
            </div>

            <!-- Edinme Kaynağı ve Fiyat Filtreleri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Edinme Kaynağı</label>
                <select name="acquisition_source_id" class="w-full rounded-md border-gray-300">
                    <option value="">Seçiniz</option>
                    @foreach($acquisitionSources as $source)
                        <option value="{{ $source->id }}">{{ $source->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat Aralığı</label>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" name="min_price" class="w-full rounded-md border-gray-300" placeholder="Min">
                    <input type="number" name="max_price" class="w-full rounded-md border-gray-300" placeholder="Max">
                </div>
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrele
                </button>
            </div>
        </form>
    </div>

    <!-- Sonuçlar -->
    <div id="results" class="bg-white rounded-lg shadow hidden">
        <!-- Sonuçlar AJAX ile yüklenecek -->
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filterForm');
    const resultsDiv = document.getElementById('results');
    const quickFilters = document.querySelectorAll('.quick-filter');
    let activeFilters = new Set();
    let hasSearched = false;

    // Form submit olayını dinle
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        hasSearched = true;
        // Form submit edildiğinde tüm hızlı filtreleri temizle
        quickFilters.forEach(btn => {
            btn.classList.remove('active');
            btn.querySelector('.fa-check').classList.add('opacity-0');
            btn.classList.remove('ring-2', 'ring-offset-2');
        });
        activeFilters.clear();
        fetchResults();
    });

    // Hızlı filtre butonlarını dinle
    quickFilters.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            hasSearched = true;
            
            // Filtre durumunu güncelle
            if (activeFilters.has(filter)) {
                // Filtre zaten aktifse, kaldır
                activeFilters.delete(filter);
                this.classList.remove('active');
                this.querySelector('.fa-check').classList.add('opacity-0');
                this.classList.remove('ring-2', 'ring-offset-2');
            } else {
                // Filtre aktif değilse, ekle
                activeFilters.add(filter);
                this.classList.add('active');
                this.querySelector('.fa-check').classList.remove('opacity-0');
                this.classList.add('ring-2', 'ring-offset-2');
            }

            // Form verilerini al
            const formData = new FormData(form);
            
            // Aktif filtreleri formData'ya ekle
            formData.delete('quick_filter'); // Önceki filtreleri temizle
            activeFilters.forEach(filter => {
                formData.append('quick_filter[]', filter);
            });

            fetchResults(formData);
        });
    });

    function fetchResults(formData = new FormData(form)) {
        if (!hasSearched) return;

        // Aktif filtre kontrolü
        const hasActiveQuickFilters = activeFilters.size > 0;
        const hasActiveFormFilters = Array.from(formData.entries()).some(([key, value]) => {
            return value !== '' && !key.includes('quick_filter');
        });

        if (!hasActiveQuickFilters && !hasActiveFormFilters) {
            resultsDiv.classList.add('hidden');
            return;
        }

        resultsDiv.classList.remove('hidden');
        
        // Loading göster
        resultsDiv.innerHTML = '<div class="p-4 text-center"><i class="fas fa-spinner fa-spin text-blue-600 text-2xl"></i></div>';
        
        const params = new URLSearchParams(formData);
        
        fetch(`{{ route('admin.book-reports.results') }}?${params.toString()}`)
            .then(response => response.text())
            .then(html => {
                resultsDiv.innerHTML = html;
            })
            .catch(error => {
                resultsDiv.innerHTML = `
                    <div class="p-4 text-center text-red-600">
                        <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                        <p>Veriler yüklenirken bir hata oluştu.</p>
                    </div>
                `;
                console.error('Hata:', error);
            });
    }

    // Aktif filtrelerin stillerini ayarla
    function updateFilterStyles() {
        quickFilters.forEach(button => {
            const filter = button.dataset.filter;
            if (activeFilters.has(filter)) {
                button.classList.add('active');
                button.querySelector('.fa-check').classList.remove('opacity-0');
                button.classList.add('ring-2', 'ring-offset-2');
            } else {
                button.classList.remove('active');
                button.querySelector('.fa-check').classList.add('opacity-0');
                button.classList.remove('ring-2', 'ring-offset-2');
            }
        });
    }
});
</script>

<style>
.quick-filter.active {
    font-weight: bold;
    transform: scale(1.05);
}

.quick-filter {
    position: relative;
    transition: all 0.2s ease;
}

.quick-filter:hover {
    transform: translateY(-1px);
}

.quick-filter.active:hover {
    transform: scale(1.05) translateY(-1px);
}

/* Renk bazlı ring stilleri */
.quick-filter[data-filter="overdue"].active { @apply ring-red-500; }
.quick-filter[data-filter="due_today"].active { @apply ring-yellow-500; }
.quick-filter[data-filter="most_borrowed"].active { @apply ring-blue-500; }
.quick-filter[data-filter="added_last_month"].active { @apply ring-green-500; }
.quick-filter[data-filter="never_borrowed"].active { @apply ring-purple-500; }
.quick-filter[data-filter="available"].active { @apply ring-emerald-500; }
.quick-filter[data-filter="active_borrowings"].active { @apply ring-indigo-500; }
</style>

@endsection 
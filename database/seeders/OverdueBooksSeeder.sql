-- Önce test verileri için 3 kitap ekleyelim (eğer yoksa)
INSERT IGNORE INTO books (id, name, isbn, publication_year, category_id, publisher_id, created_at, updated_at)
VALUES 
(1, 'Masumiyet Müzesi', '9789750826863', 2008, 1, 1, NOW(), NOW()),
(2, 'Tutunamayanlar', '9789754702699', 1972, 1, 2, NOW(), NOW()),
(3, 'Kürk Mantolu Madonna', '9789754060409', 1943, 1, 3, NOW(), NOW());

-- Her kitap için stok ekleyelim
INSERT IGNORE INTO stocks (id, book_id, barcode, status, created_at, updated_at)
VALUES 
(1, 1, 'MM001', 'borrowed', NOW(), NOW()),
(2, 1, 'MM002', 'borrowed', NOW(), NOW()),
(3, 2, 'TT001', 'borrowed', NOW(), NOW());

-- Stok durumlarını güncelle
UPDATE stocks 
SET status = 'borrowed' 
WHERE id IN (1, 3, 4);  -- Masumiyet Müzesi ve İnce Memed'in stokları

-- Gecikmiş ödünç kayıtları ekleyelim
INSERT INTO borrowings (user_id, stock_id, borrow_date, due_date, return_date, status, created_at, updated_at)
VALUES 
-- Masumiyet Müzesi - 1. kopya (15 gün gecikmiş)
(1, 1, '2025-03-22', '2025-04-22', NULL, 'active', NOW(), NOW()),

-- Masumiyet Müzesi - 2. kopya (10 gün gecikmiş)
(2, 3, '2025-03-27', '2025-04-27', NULL, 'active', NOW(), NOW()),

-- İnce Memed (5 gün gecikmiş)
(3, 4, '2025-04-01', '2025-05-01', NULL, 'active', NOW(), NOW()); 
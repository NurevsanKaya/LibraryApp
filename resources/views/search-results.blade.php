<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        .book-list {
            list-style: none;
            padding: 0;
        }
        .book-list li {
            background: #fff;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<h1>Arama Sonuçları</h1>
@if($books->isEmpty())
    <p>"{{ $query }}" ile ilgili bir sonuç bulunamadı.</p>
@else
    <ul class="book-list">
        @foreach($books as $book)
            <li>
                <strong>Kitap Adı:</strong> {{ $book->name }}<br>
                <strong>Yazar:</strong> {{ $book->authors->map(function ($author){
                 return $author->fullName();})->join(', ') ?? 'Bilinmiyor' }}<br>
                <strong>ISBN:</strong> {{ $book->isbn }}<br>
                <strong>Yayın Yılı:</strong> {{ $book->publication_year ?? 'Belirtilmemiş' }}<br>
            </li>
        @endforeach
    </ul>
@endif
</body>
</html>

@extends('layouts.layout')

@section('content')

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
        <img src="https://images.pexels.com/photos/1290141/pexels-photo-1290141.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2" alt="" data-aos="fade-in">

        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <h2 data-aos="fade-up" data-aos-delay="100">KÜTÜPHANEYE HOŞGELDİNİZ</h2>
                    <p data-aos="fade-up" data-aos-delay="200">
                        Kitapların dünyasına açılan kapı: Geniş koleksiyonumuzla bilgiye ve hayal gücüne yolculuk yapın!
                    </p>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="300">
                    <form action="/search" method="GET" class="search-form">
                        <div class="search-container">
                            <input type="text" name="query" class="search-input" placeholder="Aramak istediğiniz kelimeyi yazın..." required>
                            <button type="submit" class="search-button">Ara</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /Hero Section -->
    <style>
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 25px;
            padding: 5px 10px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 20px;
            background: transparent;
        }

        .search-input::placeholder {
            color: #aaa;
        }

        .search-button {
            background-color: #e63946;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #d62839;
        }
    </style>
@endsection

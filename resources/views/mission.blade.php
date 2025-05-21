<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - Active Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body>

<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <h1 class="sitename">KÜTÜPHANE.</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/') }}" class="active">ANASAYFA</a></li>
                <li class="dropdown">
                    <a href="#"><span>HAKKIMIZDA</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ url('/information') }}">Genel Bilgi</a></li>
                        <li><a href="{{ url('/mission') }}">Misyon & Vizyon</a></li>
                        <li><a href="{{ url('/hours') }}">Çalışma Saatleri</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/contact') }}">İLETİŞİM</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="{{ route('login') }}">Giriş Yap</a>
    </div>
</header>

<!-- Sayfa İçeriği -->
<div class="container py-5">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            color: #0077cc;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        p {
            margin: 10px 0;
            text-align: justify;
        }
    </style>

    <h1>ÇOMÜ Kütüphane ve Dokümantasyon</h1>

    <h2>Misyonumuz</h2>
    <p>
        Çanakkale Onsekiz Mart Üniversitesi Kütüphane ve Dokümantasyon Daire Başkanlığı olarak misyonumuz üniversitenin araştırma, öğrenme ve öğretme amacına hizmet etmek için gereken tüm kaynak ve servisleri temin etmek ve geliştirmektir.
    </p>

    <h2>Vizyonumuz</h2>
    <p>
        Bilimsel araştırmanın ve entelektüel sorgulamanın en iyi şekilde yapılmaya çalışıldığı üniversitede öğrenmenin ve araştırmanın vazgeçilmez merkezi olarak bilim ve gelişim konusundaki tüm kaynaklara her yerden erişimi sağlamak ve çağdaş teknolojileri kullanarak sunduğu hizmetlerle alanında öncü bir kurum olmaktır.
    </p>
</div>

@include('footer')

</body>
</html>

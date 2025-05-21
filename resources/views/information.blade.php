<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Kütüphane Hakkında</title>
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

<!-- HEADER -->
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <h1 class="sitename">KÜTÜPHANE.</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/') }}">ANASAYFA</a></li>
                <li class="dropdown"><a href="#"><span>HAKKIMIZDA</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
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

<!-- İÇERİK -->
<div class="container py-5">

    <style>
        h1 {
            text-align: center;
            color: #0077cc;
            margin-bottom: 40px;
        }

        h2 {
            color: #4CAF50;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        p, li {
            font-size: 1rem;
            color: #555;
            text-align: justify;
        }

        .content-box {
            background-color: white;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .content-box ul {
            list-style: disc;
            margin-left: 20px;
        }
    </style>

    <h1>Kütüphane Hakkında</h1>

    <div class="content-box">
        <h2>Kütüphanemiz Hakkında</h2>
        <p>
            1985 yılında kurulan kütüphanemiz, toplumun bilgiye erişimini kolaylaştırmak ve kültürel gelişimini desteklemek amacıyla hizmet vermektedir.
            Zengin koleksiyonlarımız, modern teknolojik altyapımız ve eğitim odaklı programlarımız ile herkesin erişebileceği bir bilgi merkezi olmayı hedefliyoruz.
        </p>
    </div>

    <div class="content-box">
        <h2>Sunduğumuz Hizmetler</h2>
        <ul>
            <li>Kitap Ödünç Alma</li>
            <li>Çevrimiçi Kaynak Erişimi</li>
            <li>Okuma Programları</li>
            <li>Seminerler ve Eğitimler</li>
        </ul>
    </div>

    <div class="content-box">
        <h2>Koleksiyonlarımız</h2>
        <p>
            Kütüphanemizde çeşitli koleksiyonlar bulunmaktadır. Kitaplar, dergiler, e-kitaplar ve görsel-işitsel materyallerle
            araştırmalarınızı desteklemenize yardımcı olacak geniş bir arşive sahibiz.
        </p>
    </div>
</div>

@include('footer')

</body>
</html>

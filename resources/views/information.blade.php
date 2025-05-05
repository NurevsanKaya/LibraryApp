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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- =======================================================
    * Template Name: Active
    * Template URL: https://bootstrapmade.com/active-bootstrap-website-template/
    * Updated: Aug 07 2024 with Bootstrap v5.3.3
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-xl position-relative d-flex align-items-center justify-content-between">


        <a href="index.html" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1 class="sitename">KÜTÜPHANE.</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/') }}" class="active">ANASAYFA</a></li>
                <li class="dropdown"><a href="#{{ url('/about') }}"><span>HAKKIMIZDA</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ url('/information') }}">Genel Bilgi</a></li>
                        <li><a href="{{ url('/mission') }}">Misyon & Vizyon</a></li>

                        <li><a href="{{ url('/hours') }}">Çalışma Saatleri</a></li>
                    </ul>
                </li>

                <li><a href="{{ route('login') }}">KÜTÜPHANE HESABIM</a></li>

                <li><a href="{{ url('/contact') }}">İLETİŞİM</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
        <a class="btn-getstarted" href="{{ route('login') }}">Giriş Yap</a>
    </div>
</header>


<body>
<header>
    <h1>Kütüphane Hakkında</h1>
</header>

<section class="about-library">
    <h2>Kütüphanemiz Hakkında</h2>
    <p>
        1985 yılında kurulan kütüphanemiz, toplumun bilgiye erişimini kolaylaştırmak ve kültürel gelişimini desteklemek amacıyla hizmet vermektedir.
        Zengin koleksiyonlarımız, modern teknolojik altyapımız ve eğitim odaklı programlarımız ile herkesin erişebileceği bir bilgi merkezi olmayı hedefliyoruz.
    </p>
</section>

<section class="services">
    <h2>Sunduğumuz Hizmetler</h2>
    <ul>
        <li>Kitap Ödünç Alma</li>
        <li>Çevrimiçi Kaynak Erişimi</li>
        <li>Okuma Programları</li>
        <li>Seminerler ve Eğitimler</li>
    </ul>
</section>

<section class="collections">
    <h2>Koleksiyonlarımız</h2>
    <p>
        Kütüphanemizde çeşitli koleksiyonlar bulunmaktadır. Kitaplar, dergiler, e-kitaplar ve görsel-işitsel materyallerle
        araştırmalarınızı desteklemenize yardımcı olacak geniş bir arşive sahibiz.
    </p>
</section>

<style>
    /* Genel ayarlar */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        line-height: 1.6;
        background-color: #f4f4f4;
        color: #333;
        padding: 20px;
    }

    /* Header stili */
    header {
        text-align: center;
        background-color: #4CAF50;
        color: white;
        padding: 20px 0;
        margin-bottom: 30px;
    }

    header h1 {
        font-size: 2.5rem;
    }
/* Kütüphane Hakkında bölümü */
.about-library {
background-color: white;
padding: 20px;
border-radius: 8px;
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
margin-bottom: 30px;
}

.about-library h2 {
font-size: 1.8rem;
color: #4CAF50;
margin-bottom: 10px;
}

.about-library p {
font-size: 1rem;
color: #555;
}

/* Hizmetler bölümü */
.services {
background-color: white;
padding: 20px;
border-radius: 8px;
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
margin-bottom: 30px;
}

.services h2 {
font-size: 1.8rem;
color: #4CAF50;
margin-bottom: 10px;
}

.services ul {
list-style: none;
padding-left: 20px;
}

.services ul li {
font-size: 1rem;
color: #555;
margin-bottom: 5px;
}

/* Koleksiyonlar bölümü */
.collections {
background-color: white;
padding: 20px;
border-radius: 8px;
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.collections h2 {
font-size: 1.8rem;
color: #4CAF50;
margin-bottom: 10px;
}

.collections p {
font-size: 1rem;
color: #555;
}
</style>
</body>

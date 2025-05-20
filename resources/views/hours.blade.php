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


        <a href="{{ url('/') }}" class="logo d-flex align-items-center">
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

                <li><a href="{{ url('/contact') }}">İLETİŞİM</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
        <a class="btn-getstarted" href="{{ route('login') }}">Giriş Yap</a>
    </div>
</header>




<style>




            h2 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }

            th, td {
                padding: 12px;
                border: 1px solid #ddd;
            }

            th {
                background-color: #f4f4f4;
                font-weight: bold;
                text-align: center;
            }

            td {
                vertical-align: top;
            }


        </style>

    <body>
    <div class="container">
        <h2>Kütüphane Çalışma Saatleri</h2>
        <table>
            <thead>
            <tr>
                <th>Merkez Kütüphane</th>
                <th>7 Gün / 24 Saat</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Ödünç Verme - İade</td>
                <td>7 Gün / 24 Saat</td>
            </tr>
            <tr>
                <td>-1. Kat Bilgisayar Laboratuvarı (lab1)</td>
                <td>7 Gün / 24 Saat</td>
            </tr>
            <tr>
                <td>Kütüphanelerarası Ödünç</td>
                <td>08:00 - 17:00</td>
            </tr>
            <tr>
                <td>Muhasebe</td>
                <td>08:00 - 17:00</td>
            </tr>
            </tbody>
        </table>
        <br>
        <br>
    </div>
    </body>



</html>

@extends('footer')

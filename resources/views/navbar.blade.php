<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

        <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1 class="sitename">KÜTÜPHANE</h1><span>.</span>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/') }}" class="active">ANASAYFA</a></li>
                <li class="dropdown"><a href="#{{ url('/about') }}"><span>HAKKIMIZDA</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ url('/information') }}">Genel Bilgi</a></li>
                        <li><a href="{{ url('/mission') }}">Misyon & Vizyon</a></li>
                        <li><a href="{{ url('direction') }}">Kütüphane Yönergesi</a></li>
                        <li><a href="{{ url('/hours') }}">Çalışma Saatleri</a></li>
                    </ul>
                </li>
                <li><a href="{{ url('/services') }}">HİZMETLER VE OLANAKLAR</a></li>
                <li><a href="{{ url('/myaccount') }}">KÜTÜPHANE HESABIM</a></li>

                <li><a href="{{ url('/contact') }}">İLETİŞİM</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="index.html#about">Giriş Yap</a>

    </div>
</header>

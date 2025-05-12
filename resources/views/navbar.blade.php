



<header id="header" class="header d-flex align-items-center fixed-top  ">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

        <a href="#" class="logo d-flex align-items-center me-auto me-xl-0">
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

                        <li><a href="{{ url('/hours') }}">Çalışma Saatleri</a></li>
                    </ul>
                </li>

                <li><a href="{{ route('login') }}">KÜTÜPHANE HESABIM</a></li>

                <li><a href="{{ url('/contact') }}">İLETİŞİM</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        @auth
            <div class="d-flex align-items-center">
                <span class="me-3 text-dark">{{ Auth::user()->name }}</span>
                <a href="{{ route('dashboard') }}"
                   class="btn-getstarted">
                    Hesabım
                </a>
            </div>
        @else
            <div class="d-flex align-items-center">
                <a href="{{ route('login') }}"
                   class="btn-getstarted me-2">
                    Giriş Yap
                </a>
                <a href="{{ route('register') }}"
                   class="btn-getstarted" style="background-color: #28a745;">
                    Kayıt Ol
                </a>
            </div>
        @endauth
    </div>
</header>


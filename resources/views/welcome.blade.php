@vite('resources/css/app.css')
<header class="header">
    <div class="container">
        <div class="logo">
            <h1>KÜTÜPHANE</h1>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="{{ url('/') }}">ANASAYFA</a></li>
                <li><a href="{{ url('/about') }}">HAKKIMIZDA</a></li>
                <li><a href="{{ url('/room') }}">ETKİNLİK</a></li>
                <li><a href="{{ url('/gallery') }}">ÜYELİK</a></li>
                <li><a href="{{ url('/contact') }}">İLETİŞİM</a></li>
            </ul>
            <!-- Sosyal medya ikonları -->
            <div class="social-icons">
                <a href="https://www.facebook.com" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
            <!-- Buton (Navbarın Üstüne Eklenen) -->

        </nav>
    </div>
</header>

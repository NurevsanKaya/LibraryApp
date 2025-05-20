@extends('layouts.layout')

@section('content')
    <!-- İletişim Bölümü -->
    <section id="contact" class="contact section">

        <!-- Bölüm Başlığı -->
        <div class="container section-title" data-aos="fade-up">
            <h2>İletişim</h2>
            <p>Herhangi bir sorunuz ya da öneriniz için bizimle iletişime geçebilirsiniz.</p>
        </div><!-- Bölüm Başlığı Sonu -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-6">

                    <div class="row gy-4">
                        <div class="col-md-6">
                            <div class="info-item" data-aos="fade" data-aos-delay="200">
                                <i class="bi bi-geo-alt"></i>
                                <h3>Adres</h3>
                                <p>Örnek Mahallesi, 123. Sokak No:5</p>
                                <p>Kadıköy, İstanbul 34700</p>
                            </div>
                        </div><!-- Bilgi Kartı Sonu -->

                        <div class="col-md-6">
                            <div class="info-item" data-aos="fade" data-aos-delay="300">
                                <i class="bi bi-telephone"></i>
                                <h3>Telefon</h3>
                                <p>+90 212 123 45 67</p>
                                <p>+90 530 765 43 21</p>
                            </div>
                        </div><!-- Bilgi Kartı Sonu -->

                        <div class="col-md-6">
                            <div class="info-item" data-aos="fade" data-aos-delay="400">
                                <i class="bi bi-envelope"></i>
                                <h3>E-posta</h3>
                                <p>iletisim@example.com</p>
                                <p>destek@example.com</p>
                            </div>
                        </div><!-- Bilgi Kartı Sonu -->

                        <div class="col-md-6">
                            <div class="info-item" data-aos="fade" data-aos-delay="500">
                                <i class="bi bi-clock"></i>
                                <h3>Çalışma Saatleri</h3>
                                <p>Pazartesi - Cuma</p>
                                <p>09:00 - 17:00</p>
                            </div>
                        </div><!-- Bilgi Kartı Sonu -->
                    </div>

                </div>

                <div class="col-lg-6">
                    <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Adınız" required="">
                            </div>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" placeholder="E-posta Adresiniz" required="">
                            </div>

                            <div class="col-12">
                                <input type="text" class="form-control" name="subject" placeholder="Konu" required="">
                            </div>

                            <div class="col-12">
                                <textarea class="form-control" name="message" rows="6" placeholder="Mesajınız" required=""></textarea>
                            </div>

                            <div class="col-12 text-center">
                                <div class="loading">Gönderiliyor...</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Mesajınız başarıyla gönderildi. Teşekkür ederiz!</div>

                                <button type="submit">Mesaj Gönder</button>
                            </div>

                        </div>
                    </form>
                </div><!-- İletişim Formu Sonu -->

            </div>

        </div>

    </section><!-- /İletişim Bölümü -->

@endsection

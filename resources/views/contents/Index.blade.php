@section('title', 'Inicio')

@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1" data-aos="fade-up">
                    <div>
                        <h1>Limpieza a un toque</h1>
                        <h2>Tu eliges el día y la hora para que nuestro equipo vaya a tu domicilio. Nuestra interfaz minimalista siempre tendrá en orden tus citas y agenda.</h2>
                        <a href="#" class="download-btn"><i class="bx bxl-play-store"></i> Google Play</a>
                        <a href="#" class="download-btn"><i class="bx bxl-apple"></i> App Store</a>
                    </div>
                </div>
                
                <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img" data-aos="fade-up">
                    <img src="assets/img/hero-img3.png" class="img-fluid" />
                </div>
            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= App Features Section ======= -->
        <section id="features" class="features">
            <div class="container">
                <div class="section-title">
                    <h2>Beneficios</h2>
                    <p>Estos son algunos de los beneficios que TAYD puede ofrecerte.</p>
                </div>
    
                <div class="row no-gutters">
                    <div class="col-xl-12 d-flex align-items-stretch">
                        <div class="content d-flex flex-column justify-content-center">
                            <div class="row">
                                <div class="col-md-3 icon-box" data-aos="fade-up">
                                    <img src="{{asset('assets/img/beneficios/grupo318.png')}}" alt="Imagen 1" />
                                    <h4>Aplicación móvil gratuita</h4>
                                </div>

                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="100">
                                    <img src="{{asset('assets/img/beneficios/grupo319.png')}}" alt="Imagen 2" />
                                    <h4>Aplicación móvil gratuita</h4>
                                </div>
                    
                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="200">
                                    <img src="{{asset('assets/img/beneficios/grupo320.png')}}" alt="Imagen 3" />
                                    <h4>Limpieza a un toque</h4>
                                </div>
                    
                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="300">
                                    <img src="{{asset('assets/img/beneficios/grupo321.png')}}" alt="Imagen 4" />
                                    <h4>Tu agenda el día y la hora</h4>
                                </div>
                    
                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="400">
                                    <img src="{{asset('assets/img/beneficios/grupo325.png')}}" alt="Imagen 5" />
                                    <h4>Nuestro equipo capacitado</h4>
                                </div>

                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="500">
                                    <img src="{{asset('assets/img/beneficios/grupo324.png')}}" alt="Imagen 6" />
                                    <h4>Nosotros llevamos los insumos*</h4>
                                </div>

                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="600">
                                    <img src="{{asset('assets/img/beneficios/grupo323.png')}}" alt="Imagen 7" />
                                    <h4>Paga fácil y comodamente</h4>
                                </div>

                                <div class="col-md-3 icon-box" data-aos="fade-up" data-aos-delay="700">
                                    <img src="{{asset('assets/img/beneficios/grupo322.png')}}" alt="Imagen 8" />
                                    <h4>Disponible en Villahermosa</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End App Features Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-title">
                    <h2>Contacto</h2>
                    <p>Si necesitas asesoría acerca del plan que tiene TAYD para ti, dudas o sugerencias, no dudes en contactarnos!</p>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-6 info" data-aos="fade-up">
                                <i class="bx bx-map"></i>
                                <h4>Dirección</h4>
                                <p>
                                    Av. Mario Brown Peralta #000<br />
                                    Col. Tamulté de las barrancas<br />
                                    Vhsa, Tab 86035 México<br />
                                </p>
                            </div>
                            <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="100">
                                <i class="bx bx-phone"></i>
                                <h4>Llamanos</h4>
                                <p> +1 55 (993) 279-0183</p>
                            </div>
                            <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="200">
                                <i class="bx bx-envelope"></i>
                                <h4>Escribenos</h4>
                                <p>contacto@tayd.mx</p>
                            </div>
                            <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="300">
                                <i class="bx bx-time-five"></i>
                                <h4>Horario</h4>
                                <p>Mon - Vie: 9AM a 5PM<br>Sab: 9AM a 1PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <form action="forms/contact.php" method="post" role="form" class="php-email-form" data-aos="fade-up">
                            <div class="form-group">
                                <input placeholder="Tu nombre" type="text" name="name" class="form-control" id="name" data-rule="minlen:4" data-msg="Ingresa al menos 4 caractéres" />
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <input placeholder="Tu email" type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Ingresa un email válido" />
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <input placeholder="Asunto" type="text" class="form-control" name="subject" id="subject" data-rule="minlen:4" data-msg="Ingresa al menos 8 caractéres en el asunto" />
                                <div class="validate"></div>
                            </div>
                            <div class="form-group">
                                <textarea placeholder="Mensaje" class="form-control" name="message" rows="5" data-rule="required" data-msg="Escribe algo para nosotros"></textarea>
                                <div class="validate"></div>
                            </div>
                            <div class="mb-3">
                                <div class="loading">Cargando</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Tu mensaje ha sido enviado. Gracias!</div>
                            </div>
                            <div class="text-center"><button type="submit">Enviar Mensaje</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </section><!-- End Contact Section -->
    </main>
@endsection

@include('components.Navbar')
@include('components.Footer')
@include('components.Scripts')
@include('components.Stylesheets')

@extends('components.Main')
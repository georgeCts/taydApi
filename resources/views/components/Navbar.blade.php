@section('components.Navbar')
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top  header-transparent ">
        <div class="container d-flex align-items-center">
            <div class="logo mr-auto">
                <a href="/"><img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="img-fluid"></a>
            </div>

            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="/">Inicio</a></li>
                    <li><a href="#features">Beneficios</a></li>
                    <li><a href="#contact">Contacto</a></li>
                </ul>
            </nav><!-- .nav-menu -->
        </div>
    </header><!-- End Header -->
@endsection
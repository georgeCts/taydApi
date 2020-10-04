<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="description" content="TAYD es la mejor app para solicitar servicios de limpieza, lavado de vehículos, jardinería y muchos más..">
    <meta content="" name="keywords" />

    <!-- Twitter meta-->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:site" content="@TAYD_MX" />
    <meta property="twitter:creator" content="@TAYD_MX" />

    <!-- Open Graph Meta-->
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="TAYD - Servicios" />
    <meta property="og:title" content="TAYD - " />
    <meta property="og:url" content="http://tayd.mx" />
    <meta property="og:image" content="{{ asset('img/tayd_graph_meta.jpg') }}" />
    <meta property="og:description" content="TAYD es la mejor app para solicitar servicios de limpieza, lavado de vehículos, jardinería y muchos más.." />
    
    <title>{{$_PAGE_TITLE}} | @yield('title', '*** TITLE ***')</title>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon_io/apple-touch-icon.png')}}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon_io/favicon-32x32.png')}}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon_io/favicon-16x16.png')}}" />
    <link rel="manifest" href="{{ asset('assets/img/favicon_io/site.webmanifest') }}">

    @yield('components.Stylesheets')
</head>

<body>
    @yield('components.Navbar')

    @yield('content', '*** CONTENT ***')

    @yield('components.Footer')
    
    @yield('components.Scripts')
</body>
</html>
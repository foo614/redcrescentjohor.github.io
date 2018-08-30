<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<!-- Google Analytics -->
<script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        
        ga('create', 'UA-86542784-2', 'auto');
        ga('send', 'pageview');
</script>
<!-- End Google Analytics -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    {{-- <link rel="manifest" href="{{url('/manifest.json')}}"> --}}
    <meta name="theme-color" content="#fff"/>

    <title>Red Crescent Johor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"> --}}

    {{-- icons for IOS devices --}}
    <link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/apple-167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-180.png">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Styles -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/material.red-blue.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/getmdl-select.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/metis-menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">

    <!-- Scripts -->
    <script src="{{asset('js/material.min.js')}}"></script>
    <script src="{{asset('js/getmdl-select.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIO4lZGXUhTkuxgNUgda6_JeMXBKgegok&libraries=places,geometry&callback=initMap"></script>
    <script src="{{asset('js/metis-menu.js')}}"></script>
    <script src="{{asset('js/mdl_component.js')}}"></script>
    <script src="{{asset('js/google-map.js') }}"></script>
</head>
<body class="mdl-color--grey-100">
    <div id="app" class="mdl-layout mdl-js-layout mdl-layout--fixed-header  {{ (Request::path() == 'search' && Auth::check()) ? '' : Auth::check() ? 'mdl-layout--fixed-drawer'  : '' }}">
        @include('inc.navbar')
        <main class="mdl-layout__content {{ Request::path() ==  'login' ? 'margin-form-login' : ''  }}">
                <nav aria-label="breadcumb" style="{{ Request::path() ==  'search' || Request::path() == 'login' || Request::path() == 'register' ? 'display:none' : ''  }}">
                    <ol class="breadcrumb">
                        @yield('breadcrumbs')
                    </ol>
                </nav>
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $("#menu1").metisMenu();
    </script>
    @yield('footer-scripts')
</body>
</html>
{{-- <script>
    if ('serviceWorker' in navigator ) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                // Registration was successful
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script> --}}
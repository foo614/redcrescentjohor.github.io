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
    <style> [v-cloak] { display: none; } </style>
    <!-- Styles -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">
    <link rel="stylesheet" href="{{asset('css/material.red-blue.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/getmdl-select.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link href="{{asset('css/vuetify.min.css')}}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('js/material.min.js')}}"></script>
    <script src="{{asset('js/getmdl-select.min.js')}}"></script>
    @if (Route::currentRouteName() === 'posts.create' || Route::currentRouteName() === 'posts.edit' || Route::currentRouteName() === 'posts.calendar')
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIO4lZGXUhTkuxgNUgda6_JeMXBKgegok&libraries=places,geometry"></script>
    @else
        <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIO4lZGXUhTkuxgNUgda6_JeMXBKgegok&libraries=places,geometry&callback=initMap"></script>
    @endif
    
    <script type="text/javascript">
        window.csrf_token = "{{ csrf_token() }}"
    </script>
</head>
<body>
    <div id="app">
        <v-app id="inspire" >
            <navbar 
                auth="{{ Auth::user() ? Auth::user()->toJson() : '' }}"
                auth-check="{{Auth::check()}}">
            </navbar>
            <v-content>
                <v-container>
                    @yield('content')
                </v-container>
            </v-content>
        </v-app>
    </div>
    {{-- @include('scripts.toastr') --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{asset('js/google-map.js') }}"></script>
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

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
    {{-- <meta name="theme-color" content="#fff"/> --}}
    <title>Red Crescent Johor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- icons for IOS devices --}}
    <link rel="apple-touch-icon" sizes="36x36" href="/icons/png/36x36.png">
    <link rel="apple-touch-icon" sizes="64x64" href="/icons/png/64x64.png">
    <link rel="apple-touch-icon" sizes="128x128" href="/icons/png/128x128.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/icons/png/144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/png/152x152.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/png/192x192.png">
    <link rel="apple-touch-icon" sizes="256x256" href="/icons/png/256x256.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/icons/png/512x512.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <style> [v-cloak] { display: none; } a{text-decoration: none}</style>
    <!-- Styles -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">
    <link href="{{asset('css/vuetify.min.css')}}" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIO4lZGXUhTkuxgNUgda6_JeMXBKgegok&libraries=places,geometry"></script>
    <script type="text/javascript">
        window.csrf_token = "{{ csrf_token() }}"
    </script>
</head>
<body>
    <div id="app">
        <v-app id="inspire">
            @if (Route::currentRouteName() != 'home')
            <fld-navbar
                auth="{{ Auth::user() ? Auth::user()->toJson() : '' }}"
                auth-check="{{Auth::check()}}">
            </fld-navbar>
            @endif
            <v-content>
                @if (Route::currentRouteName() != 'home' && Route::currentRouteName() != 'login')
                    <v-container>
                        @yield('content')
                        <router-view></router-view>
                    </v-container>  
                @else
                    @yield('content')
                    <router-view></router-view>
                @endif
            </v-content>
        </v-app>
    </div>
    <div id="embed-api-auth-container"></div>
    <div id="chart-container"></div>
    <div id="view-selector-container"></div>
    <script src="{{ asset('js/app.js') }}"></script>
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

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
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{asset('js/material.min.js')}}"></script>
    <script src="{{asset('js/getmdl-select.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery"></script>
    <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIO4lZGXUhTkuxgNUgda6_JeMXBKgegok&libraries=places,geometry&callback=initMap"></script>
    <script src="{{asset('js/metis-menu.js')}}"></script>
    <script src="{{asset('js/mdl_component.js')}}"></script>
    <script src="{{asset('js/google-map.js') }}"></script>
</head>
<body>
    <div id="app">
    @include('inc.navbar')
        <v-content>
                @auth
    <!-- Right aligned menu below button -->
    <button id="drop-item" class="theme--dark" style="float:right">
        @if (Auth::user()->avatar != null)
            <v-avatar size="36">
                <img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="userAvatar">
            </v-avatar>
        @else
            <v-avatar color="teal lighten-2">
            <span class="white--text headline">{{substr(Auth::user()->name, 0, 1)}}</span>                
            </v-avatar>
        @endif
    </button>
    <ul class="mdl-menu mdl-js-menu mdl-js-ripple-effect mdl-menu--bottom-right demo-list-icon mdl-list" style="padding-top:0;" for="drop-item">
        <li class="mdl-menu__item mdl-list__item--two-line" style="width: 280px;height: 72px; background-color:#eeeeee;padding:10px;">
            <span class="mdl-list__item-primary-content">
            <v-avatar style="margin-right: 16px;" class="material-icons mdl-list__item-avatar"><img src="https://avatars0.githubusercontent.com/u/9064066?v=4&s=460" alt="Avatar"></v-avatar>
            <span>{{Auth::user()->name}}</span>
            <span class="mdl-list__item-sub-title">{{Auth::user()->email}}</span>
        </li>
        <li class="mdl-menu__item mdl-list__item">
            <span class="mdl-list__item-primary-content">
                <i class="material-icons mdl-list__item-icon">account_box</i>Profile
            </span>
        </li>
        <li class="mdl-menu__item mdl-list__item">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="logout" style="text-decoration: none; color: rgba(0,0,0,.87);">
                <span class="mdl-list__item-primary-content">
                    <i class="material-icons mdl-list__item-icon">save_alt</i>Sign out
                </span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </li>
    </ul>
    @endauth
            @yield('content')
        </v-content>
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
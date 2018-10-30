<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <title>Red Crescent Johor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style> [v-cloak] { display: none; } a{text-decoration: none}</style>
    <!-- Styles -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">
    <link href="{{asset('css/vuetify.min.css')}}" rel="stylesheet">
    <link rel="icon" href="{!! asset('/img/64x64.png') !!}"/>
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//cdn.ckeditor.com/4.10.1/full-all/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/4.5.11/full-all/plugins/divarea/plugin.js"></script>
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
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
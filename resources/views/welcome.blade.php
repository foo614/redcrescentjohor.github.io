<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="{{url('/manifest.json')}}">
        <meta name="theme-color" content="#fff"/>

        {{-- icons for IOS devices --}}
        <link rel="apple-touch-icon" sizes="60x60" href="/icons/apple-60.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/icons/apple-76.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/icons/apple-120.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/icons/apple-152.png">
        <link rel="apple-touch-icon" sizes="167x167" href="/icons/apple-167.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-180.png">
        <meta name="apple-mobile-web-app-capable" content="yes">

        {{-- checks for service worker support.if you have the push manager package then use this line 
        if ('serviceWorker' in navigator && 'PushManager' in window) instead of 
        if ('serviceWorker' in navigator ) --}}
        <script>
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
        </script>

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif
            <div class="content">
            </div>
        </div>
    </body>
</html>

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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/brands.css" integrity="sha384-Px1uYmw7+bCkOsNAiAV5nxGKJ0Ixn5nChyW8lCK1Li1ic9nbO5pC/iXaq27X5ENt" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/fontawesome.css" integrity="sha384-BzCy2fixOYd0HObpx3GMefNqdbA7Qjcc91RgYeDjrHTIEXqiF00jKvgQG0+zY/7I" crossorigin="anonymous">
    <style>
        .v-parallax__content{
            background: linear-gradient(0deg,rgba(13, 3, 29, 0.34),rgba(25,25,38,.85))
        }
        .parallax-banner .v-parallax__content{
            background: linear-gradient(0deg,rgba(13,3,29,.85),rgba(25,25,38,.85))
        }
        .alpha-footer{
            background: #303c42;
            color: #fff;
            display: block;
            padding: 0 !important;
            height: auto;
        }
        .alpha-footer .container, .alpha-footer ul{
            padding: 0;
            margin: 0;
        }
        .alpha-footer ul{
            list-style-type: none;
        }
        .alpha-footer ul li{
            margin-bottom: 10px;
        }
        .alpha-footer ul a{
            color: #fff;
            text-decoration: none;
            transition: .2s ease-in;
        }
        .alpha-footer ul a:hover{
            opacity: .5
        }
        .alpha-testimonial__content {
            -ms-flex-align: start;
            align-items: flex-start;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-pack: justify;
            justify-content: space-between
        }
        .primary-home--text  {
        color: #ff8040 !important;
        }
        .primary-home--line {
            background-color: #ff8040 !important;
        }
        .headline {
            font-size: 24px!important;
            font-weight: 400;
            line-height: 32px!important;
            letter-spacing: normal!important;
        }
        .alpha-testimonial__footer-bar {
            height: 4px;
            margin: 20px 0 20px auto;
            width: 100px
        }

        .alpha-testimonial .icon {
            -ms-flex-pack: end;
            justify-content: flex-end;
            -ms-flex: 0 1 10%;
            flex: 0 1 10%;
            margin-top: -8px;
            transform: rotate(180deg)
        }

        .alpha-testimonial p {
            -ms-flex: 1 1 85%;
            flex: 1 1 85%;
            line-height: 2
        }

        .alpha-testimonial footer {
            -ms-flex: 1 0 auto;
            flex: 1 0 auto;
            text-align: right
        }

        .alpha-testimonial footer .title {
            font-weight: 300
        }

        .alpha-testimonial--dark {
            color: #fff
        }
    </style>
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
            <home-header></home-header>
            <v-content>
                @yield('content')
                <router-view></router-view>
            </v-content>
            <home-footer></home-footer>
        </v-app>
    </div>
    <div id="embed-api-auth-container"></div>
    <div id="chart-container"></div>
    <div id="view-selector-container"></div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
<script>
    window.fbAsyncInit = function() {
        FB.init({
        appId            : '1068803136585486',
        autoLogAppEvents : true,
        xfbml            : true,
        version          : 'v3.1'
        });
    };
    
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
</script>
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

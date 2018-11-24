<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=0">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="MMEX - WebApp">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    
    <script src="https://ft-polyfill-service.herokuapp.com/v2/polyfill.min.js?features=default,Array.prototype.includes"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Home') - {{config('app.name')}}</title>

@include('partials.icons')

<!-- Custom styles for this template -->
    <link href="{{mix('css/vendor.css')}}" rel="stylesheet">
    <link href="{{mix('css/kendo.css')}}" rel="stylesheet">
    <link href="{{mix('css/app.css')}}" rel="stylesheet">

@yield('header')
@stack('header')

<!-- Scripts -->
    <script>
        window.App =@json([
            'csrfToken' => csrf_token(),
            'apiToken'  => Auth::check() ? Auth::user()->api_token : ""
        ])

    </script>
</head>

<body>

@include('partials.nav')

<div class="container" id="app">
    @yield('content')
</div>

<footer class="footer">
    <div class="container">
                <span class="text-muted">Web Money Manager EX |
                    <a href="https://github.com/okaufmann/laravel-web-money-manager-ex" target="_blank">
                        <i class="fa fa-github"></i><span class="hidden-xs"> Github</span></a> | &copy; {{date('Y')}} by <a
                            href="https://okaufmann.ch">okaufmann</a> </span>
    </div>
</footer>

{{--@if(App::environment('local', 'staging'))--}}
{{--<script src="//localhost:6001/socket.io/socket.io.js"></script>--}}
{{--@endif--}}

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
@if(App::environment('production'))
    <script src="{{mix('js/messages.js')}}"></script>
@else
    <script src="{{asset('js/messages.js')}}"></script>
@endif
<script src="{{mix('js/vendor.js')}}"></script>

@if(isset($globalUser))
    <script src="{{mix('js/i18n/messages-'.$globalUser->locale.'.js')}}"></script>
    <script type="text/javascript">
        Lang.setLocale("{{$globalUser->language}}");
        kendo.culture("{{$globalUser->localeKendo}}");
        moment.locale('{{$globalUser->localeMoment}}');
    </script>
@else
    <script src="{{mix('js/i18n/messages-en_US.js')}}"></script>
    <script type="text/javascript">
        Lang.setLocale("en");
        kendo.culture("en-US");
        moment.locale('en');
    </script>
@endif

<script src="{{mix('js/app.js')}}"></script>

@yield('footer')
@stack('footer')
</body>
</html>

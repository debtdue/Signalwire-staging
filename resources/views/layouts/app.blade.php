<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="RedTailMarketing Application">
    <meta name="keywords" content="Twilio, responsive, voip, communications, dashboard, admin">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">

    <title>{{ config('app.name') }} | @yield('title') </title>
    <!-- =============== VENDOR STYLES ===============-->
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/brands.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/regular.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/solid.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/animate.css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/whirl/dist/whirl.css')}}">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}" id="bscss">
    <link rel="stylesheet" href="{{asset('css/app.css')}}" id="maincss">

    @yield('page_styles')
</head>

<body>
<div class="wrapper">
    <!-- top navbar-->
@include('layouts.nav')

<!-- sidebar-->
@include('layouts.sidebar')

<!-- offsidebar-->
@include('layouts.offsidebar')

<!-- Main section-->
    <section class="section-container">
        <!-- Page content-->
        @yield('content')
    </section>

    <!-- Page footer-->
    <footer class="footer-container">
        <div class="float-left">
            <span>{{ config('app.name') }} 2.0
            <span> - All Rights Reserved</span>
                <span class="mr-2">&copy;</span>{{date('Y')}}</span>
        </div>
        <div class="float-right"> Developed By <a href="http://cruisebrains.com">Adnan Shabbir</a></div>
    </footer>
</div>

<!-- =============== APP SCRIPTS ===============-->
<script src="{{asset('vendor/modernizr/modernizr.custom.js')}}"></script>
<script src="{{asset('vendor/js-storage/js.storage.js')}}"></script>
<script src="{{asset('vendor/screenfull/dist/screenfull.js')}}"></script>
<script src="{{asset('vendor/i18next/i18next.js')}}"></script>
<script src="{{asset('vendor/i18next-xhr-backend/i18nextXHRBackend.js')}}"></script>
<script src="{{asset('vendor/jquery/dist/jquery.js')}}"></script>
<script src="{{asset('vendor/popper.js/dist/umd/popper.js')}}"></script>
<script src="{{asset('vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>

@yield('page_script')
</body>

</html>

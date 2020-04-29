<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="RedTailMarketing Application">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- =============== VENDOR STYLES ===============-->
    <!-- FONT AWESOME-->
    {{--<link rel="stylesheet" href="../vendor/fontawesome/css/font-awesome.min.css">--}}
    <link rel="stylesheet" href="{{asset('vendor/fontawesome/css/font-awesome.min.css')}}">

    <!-- SIMPLE LINE ICONS-->
    {{--<link rel="stylesheet" href="../vendor/simple-line-icons/css/simple-line-icons.css">--}}
    <link rel="stylesheet" href="{{asset('vendor/simple-line-icons/css/simple-line-icons.css')}}">
    <!-- =============== BOOTSTRAP STYLES ===============-->
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}" id="bscss">
    <!-- =============== APP STYLES ===============-->
    <link rel="stylesheet" href="{{asset('css/app.css')}}" id="maincss">
</head>

<body>
<div class="wrapper">
    <div class="block-center mt-xl wd-xl">
        <!-- START panel-->
        <div class="panel panel-dark panel-flat">
            <div class="panel-heading text-center">
                <!-- Branding Image -->
                <a href="{{ url('/') }}">
                    <img class="block-center img-rounded" src="{{asset('img/logo.png')}}" alt="Image" title="{{config('app.name', 'Laravel')}}">
                </a>

            </div>
            <div class="panel-body">
                <p class="text-center pv">SIGN IN TO CONTINUE.</p>
                <form class="mb-lg" role="form" data-parsley-validate="" novalidate="">
                    <div class="form-group has-feedback">
                        <input class="form-control" id="exampleInputEmail1" type="email" placeholder="Enter email"
                               autocomplete="off" required>
                        <span class="fa fa-envelope form-control-feedback text-muted"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input class="form-control" id="exampleInputPassword1" type="password" placeholder="Password"
                               required>
                        <span class="fa fa-lock form-control-feedback text-muted"></span>
                    </div>
                    <div class="clearfix">
                        <div class="checkbox c-checkbox pull-left mt0">
                            <label>
                                <input type="checkbox" value="" name="remember">
                                <span class="fa fa-check"></span>Remember Me</label>
                        </div>
                        <div class="pull-right"><a class="text-muted" href="recover.html">Forgot your password?</a>
                        </div>
                    </div>
                    <button class="btn btn-block btn-primary mt-lg" type="submit">Login</button>
                </form>
                <p class="pt-lg text-center">Need to Signup?</p><a class="btn btn-block btn-default"
                                                                   href="{{route('register')}}">Register Now</a>
            </div>
        </div>
        <!-- END panel-->
        <div class="p-lg text-center">
            <span>&copy;</span>
            <span>2017</span>
            <span>-</span>
            <span>Angle</span>
            <br>
            <span>Bootstrap Admin Template</span>
        </div>
    </div>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<!-- MODERNIZR-->
<script src="{{asset('vendor/modernizr/modernizr.custom.js')}}"></script>
<!-- JQUERY-->
<script src="{{asset('vendor/jquery/dist/jquery.js')}}"></script>
<!-- BOOTSTRAP-->
<script src="{{asset('vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
<!-- STORAGE API-->
<script src="{{asset('vendor/jQuery-Storage-API/jquery.storageapi.js')}}"></script>
<!-- PARSLEY-->
<script src="{{asset('vendor/parsleyjs/dist/parsley.min.js')}}"></script>
<!-- =============== APP SCRIPTS ===============-->
<script src="js/app.js"></script>
</body>

</html>
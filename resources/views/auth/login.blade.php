<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="RedTailMarketing Application">
    <meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- =============== VENDOR STYLES ===============-->

    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/brands.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/regular.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/solid.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/fontawesome.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}" id="maincss">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}" id="bscss">
    <link rel="stylesheet" href="{{asset('vendor/simple-line-icons/css/simple-line-icons.css')}}">
    <!-- SIMPLE LINE ICONS-->
</head>

<body>
<div class="wrapper">
    <div class="block-center mt-4 wd-xxl" style="margin-top: 8% !important;">
        <!-- START card-->
        <div class="card card-flat">
            <div class="card-header text-center bg-dark">

                <h3><a href="{{ url('/') }}" style="color:white;">TextCall</a></h3>

            </div>

            <div class="card-body">

                <p class="text-center py-2">SIGN IN TO CONTINUE.</p>

                <form class="mb-3" method="POST" action="{{ route('login') }}" role="form" data-parsley-validate="" novalidate="" id="loginForm">
                    @csrf

                    {{--Email address--}}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                        <div class="input-group with-focus" >
                            <input class="form-control border-right-0" name="email" id="email" type="email"
                                   placeholder="Enter email address" autocomplete="off" required value="{{ old('email') }}">
                            <div class="input-group-append">
                                <span class="input-group-text text-muted bg-transparent border-left-0"><em
                                        class="fa fa-envelope"></em></span>
                            </div>
                        </div>
                        @if ($errors->has('email'))
                            <ul class="parsley-errors-list filled">
                                <li class="parsley-required">{{ $errors->first('email') }}</li>
                            </ul>
                        @endif
                    </div>

                    {{--Password--}}
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                        <div class="input-group with-focus">
                            <input class="form-control border-right-0" id="exampleInputPassword1" name="password"
                                   type="password" placeholder="Password" required>
                            <div class="input-group-append">
                                <span class="input-group-text text-muted bg-transparent border-left-0"><em
                                        class="fa fa-lock"></em></span>
                            </div>
                        </div>
                        @if ($errors->has('password'))
                            <ul class="parsley-errors-list filled">
                                <li class="parsley-required">{{ $errors->first('password') }}</li>
                            </ul>
                        @endif
                    </div>

                    {{-- Remember-Me checkbox --}}
                    <div class="clearfix">
                        <div class="checkbox c-checkbox  mt-3">
                            <label>
                                <input type="checkbox" value="" name="remember">
                                <span class="fa fa-check"></span> Remember Me
                            </label>
                        </div>
                        <div class="text-center">
                            <a class="text-muted" href="{{ route('password.request') }}">Forgot your password?</a>
                        </div>
                    </div>

                    <button class="btn btn-block btn-primary mt-3" type="submit">Login</button>
                </form>
                <p class="pt-3 text-center">Need to Signup?</p>
                <a class="btn btn-block btn-secondary" href="{{route('register')}}">Register Now</a>
            </div>
        </div>
        <!-- END card-->

        <div class="p-3 text-center">
            <span>{{ config('app.name') }} <span class="mr-2">&copy;</span>{{date('Y')}}</span>
            <span class="mr-2">- All Rights Reserved</span>
        </div>
    </div>
</div>
<!-- =============== VENDOR SCRIPTS ===============-->
<script src="{{asset('vendor/js-storage/js.storage.js')}}"></script>
<script src="{{asset('vendor/i18next/i18next.js')}}"></script>
<script src="{{asset('vendor/i18next-xhr-backend/i18nextXHRBackend.js')}}"></script>
<script src="{{asset('vendor/popper.js/dist/umd/popper.js')}}"></script>
<script src="{{asset('vendor/parsleyjs/dist/parsley.js')}}"></script>
<script src="{{asset('js/app.js')}}"></script>
<script src="{{asset('vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
<script src="{{asset('vendor/jquery/dist/jquery.js')}}"></script>
<script src="{{asset('vendor/modernizr/modernizr.custom.js')}}"></script>
</body>

</html>

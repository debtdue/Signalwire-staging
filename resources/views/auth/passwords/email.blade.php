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
    <link rel="stylesheet" href="{{asset('vendor/simple-line-icons/css/simple-line-icons.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}" id="bscss">
    <link rel="stylesheet" href="{{asset('css/app.css')}}" id="maincss">
</head>

<body>
    <div class="wrapper">
        <div class="block-center mt-4 wd-xl">
            <!-- START card-->
            <div class="card card-flat">
                <div class="card-header text-center bg-dark">
                    {{-- <a href="{{ url('/') }}">
                        <img class="block-center rounded" src="{{asset('img/logo.png')}}" alt="Image" title="{{config('app.name', 'Laravel')}}">
                    </a> --}}
                    <h3 style="color:white;">TextCall-Signalwire</h3>
                </div>
                <div class="card-body">
                    <p class="text-center py-2">PASSWORD RESET</p>

                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" role="form">
                        {{ csrf_field() }}

                        <p class="text-center">Fill with your mail to receive instructions on how to reset your password.</p>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                            <label class="text-muted" for="resetInputEmail1">Email address</label>
                            <div class="input-group with-focus">
                                <input class="form-control border-right-0" id="resetInputEmail1" type="email" placeholder="Enter email" autocomplete="off" name="email" value="{{ old('email') }}" required>

                                <div class="input-group-append">
                                    <span class="input-group-text text-muted bg-transparent border-left-0">
                                        <em class="fa fa-envelope"></em>
                                    </span>
                                </div>
                            </div>

                            @if ($errors->has('email'))
                            <ul class="parsley-errors-list filled">
                                <li class="parsley-required">{{ $errors->first('email') }}</li>
                            </ul>
                            @endif

                        </div>
                        <button class="btn btn-danger btn-block" type="submit">Reset</button>
                    </form>

                </div>
            </div>
            <!-- END card-->
            <div class="p-3 text-center">
                <span class="mr-2">&copy;</span>
                <span>{{date('Y')}}</span>
                <span class="mr-2">-</span>
                <span>All Rights Reserved</span>
                <br>
                <span>{{ config('app.name') }}</span>
            </div>
        </div>
    </div>

    <!-- MODERNIZR-->
    <script src="{{asset('vendor/js-storage/js.storage.js')}}"></script><!-- i18next-->
    <script src="{{asset('vendor/i18next/i18next.js')}}"></script>
    <script src="{{asset('vendor/i18next-xhr-backend/i18nextXHRBackend.js')}}"></script><!-- JQUERY-->
    <script src="{{asset('vendor/popper.js/dist/umd/popper.js')}}"></script>
    <script src="{{asset('vendor/parsleyjs/dist/parsley.js')}}"></script>
    <script src="{{asset('vendor/modernizr/modernizr.custom.js')}}"></script>
    <script src="{{asset('vendor/jquery/dist/jquery.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
    <script src="{{asset('js/app.js')}}"></script>
</body>

</html>
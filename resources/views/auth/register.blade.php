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
    
    <!-- FONT AWESOME-->
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/brands.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/regular.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/solid.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/fontawesome.css')}}"><!-- SIMPLE LINE ICONS-->
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
                    {{-- <a href="#">
                      <img class="block-center" src="img/logo.png" alt="Image">
                    </a> --}}
                    <h3 style="color:white;">TextCall-Signalwire</h3>
                </div>
              <div class="card-body">
                 <p class="text-center py-2">SIGNUP TO GET INSTANT ACCESS.</p>
                 
                 <form method="POST" action="{{ route('register') }}" class="mb-3" id="registerForm" role="form" data-parsley-validate="" novalidate="">
                    {{ csrf_field() }}

                    {{-- FULL NAME --}}
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} has-feedback">
                        <label class="text-muted" for="signupInputName">Full Name</label>
                        <div class="input-group with-focus">

                           <input class="form-control border-right-0" id="signupInputName" name="name" type="text" placeholder="Enter your full name" autocomplete="off" value="{{ old('name') }}" required autofocus>

                            <div class="input-group-append">
                              <span class="input-group-text text-muted bg-transparent border-left-0">
                                  <em class="fa fa-user"></em>
                                </span>
                            </div>
                        </div>

                       @if ($errors->has('name'))
                        <ul class="parsley-errors-list filled" >
                            <li class="parsley-required">{{ $errors->first('name') }}</li>
                        </ul>
                        @endif
                    </div>

                    {{-- EMAIL --}}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                        <label class="text-muted" for="signupInputEmail1">Email address</label>
                        <div class="input-group with-focus">

                           <input class="form-control border-right-0" id="signupInputEmail1" name="email" type="email" placeholder="Enter email" autocomplete="off" value="{{ old('email') }}" required>

                            <div class="input-group-append">
                              <span class="input-group-text text-muted bg-transparent border-left-0">
                                  <em class="fa fa-envelope"></em>
                                </span>
                            </div>
                        </div>

                       @if ($errors->has('email'))
                        <ul class="parsley-errors-list filled" >
                            <li class="parsley-required">{{ $errors->first('email') }}</li>
                        </ul>
                        @endif
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                        <label class="text-muted" for="signupInputPassword1">Password</label>

                       <div class="input-group with-focus">
                           <input class="form-control border-right-0" name="password" id="signupInputPassword1" type="password" placeholder="Password" autocomplete="off" required>


                            <div class="input-group-append">
                              <span class="input-group-text text-muted bg-transparent border-left-0">
                                  <em class="fa fa-lock"></em>
                                </span>
                            </div>
                       </div>

                        @if ($errors->has('password'))
                        <ul class="parsley-errors-list filled">
                            <li class="parsley-required">{{ $errors->first('password') }}</li>
                        </ul>
                        @endif

                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div class="form-group">
                        <label class="text-muted" for="signupInputRePassword1">Retype Password</label>
                       <div class="input-group with-focus">

                           <input class="form-control border-right-0" name="password_confirmation" id="signupInputRePassword1" type="password" 
                                    placeholder="Retype Password" autocomplete="off" required data-parsley-equalto="#signupInputPassword1">

                            <div class="input-group-append">
                              <span class="input-group-text text-muted bg-transparent border-left-0">
                                  <em class="fa fa-lock"></em>
                                </span>
                            </div>
                       </div>
                    </div>

                    {{-- I AGREE CHECKBOX --}}
                    <div class="checkbox c-checkbox mt-0">
                        <label>
                            <input type="checkbox" value="" required name="agreed">
                            <span class="fa fa-check"></span> I agree with the <a class="ml-1" href="#">terms</a>
                        </label>
                    </div>

                    <button class="btn btn-block btn-primary mt-3" type="submit">Create account</button>
                 </form>

                 <p class="pt-3 text-center">Have an account?</p>
                 <a class="btn btn-block btn-secondary" href="{{route('login')}}">Sign in</a>
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
   <script src="{{asset('vendor/modernizr/modernizr.custom.js')}}"></script>
   <script src="{{asset('vendor/js-storage/js.storage.js')}}"></script><!-- i18next-->
   <script src="{{asset('vendor/i18next/i18next.js')}}"></script>
   <script src="{{asset('vendor/i18next-xhr-backend/i18nextXHRBackend.js')}}"></script><!-- JQUERY-->
   <script src="{{asset('vendor/jquery/dist/jquery.js')}}"></script>
   <script src="{{asset('vendor/popper.js/dist/umd/popper.js')}}"></script>
   <script src="{{asset('vendor/bootstrap/dist/js/bootstrap.js')}}"></script>
   <script src="{{asset('vendor/parsleyjs/dist/parsley.js')}}"></script>
   <script src="{{asset('js/app.js')}}"></script>

</body>

</html>
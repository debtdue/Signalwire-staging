@extends('layouts.app')

@section('title')
    Profile
@endsection

@section('content')

    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Profile<small style="margin-top: 5% !important;">Update basic user profile</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
                @include('layouts.flash_messages')

                <!-- START card-->
                <div class="card card-default">

                    <div class="card-body">

                        <form class="form-horizontal" method="post" action="{{route('update_profile')}}">
                            @csrf

                            {{--Full name--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="name">Full Name</label>
                                    <div class="col-sm-6">
                                        <input id="name" class="form-control {{ $errors->has('name') ? 'parsley-error' : '' }}" type="text" name="name" value="{{old('name', $profile->name)}}" placeholder="Enter your full name">

                                        @if ($errors->has('name'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('name') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Email Address--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                                    <div class="col-sm-6">
                                        <input id="email" class="form-control {{ $errors->has('email') ? 'parsley-error' : '' }}" name="email" type="email" value="{{old('email', $profile->email)}}" placeholder="Enter your email address">

                                        @if ($errors->has('email'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('email') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Password--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-6">
                                        <input id="password" class="form-control {{ $errors->has('password') ? 'parsley-error' : '' }}" name="password" type="password" placeholder="Enter your password here">

                                        @if ($errors->has('password'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('password') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Password Confirmation--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                    <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
                                    <div class="col-sm-6">
                                        <input id="password_confirmation" class="form-control {{ $errors->has('password_confirmation') ? 'parsley-error' : '' }}" name="password_confirmation" type="password" placeholder="Re enter your password here">

                                        @if ($errors->has('password_confirmation'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('password_confirmation') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Update Profile--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Update Profile</button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>
                {{-- END CARD --}}
            </div>
        </div>
    </div>
@endsection

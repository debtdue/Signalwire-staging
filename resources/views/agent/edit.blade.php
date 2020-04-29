@extends('layouts.app')

@section('title')
    Edit Agent
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Update Agent {{ $agent->first_name . " " .  $agent->last_name }}<small style="margin-top: 5%;">Update agent's details</small></div>
        </div>

        <!-- START widgets box-->
    @include('layouts.flash_messages')

    <!-- START card-->
        <div class="card card-default">
            <div class="card-body">
                <form class="form-horizontal" method="post" action="{{route('agents.update', $agent->id)}}">
                    @csrf
                    @method('PUT')

                    {{--First name--}}
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="first_name">First Name</label>
                            <div class="col-sm-6">
                                <input id="first_name"
                                       class="form-control {{ $errors->has('first_name') ? 'parsley-error' : '' }}"
                                       type="text" name="first_name" value="{{old('first_name', $agent->first_name)}}"
                                       placeholder="Enter your first name">
                                @if ($errors->has('first_name'))
                                    <ul class="parsley-errors-list filled">
                                        <li class="parsley-required">{{ $errors->first('first_name') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    {{--Last name--}}
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label" for="last_name">Last Name</label>
                            <div class="col-sm-6">
                                <input id="last_name"
                                       class="form-control {{ $errors->has('last_name') ? 'parsley-error' : '' }}"
                                       type="text" name="last_name" value="{{old('last_name', $agent->last_name)}}"
                                       placeholder="Enter your last name">
                                @if ($errors->has('last_name'))
                                    <ul class="parsley-errors-list filled">
                                        <li class="parsley-required">{{ $errors->first('last_name') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    {{--Phone Number--}}
                    <fieldset>
                        <div class="form-group row">
                            <label for="phone_number" class="col-sm-2 col-form-label">Phone Number</label>
                            <div class="col-sm-6">
                                <input id="phone_number"
                                       class="form-control {{ $errors->has('phone_number') ? 'parsley-error' : '' }}"
                                       name="phone_number" value="{{old('phone_number', $agent->phone_number)}}"
                                       type="text" placeholder="Enter your phone number">
                                @if ($errors->has('phone_number'))
                                    <ul class="parsley-errors-list filled">
                                        <li class="parsley-required">{{ $errors->first('phone_number') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    {{--Email Address--}}
                    <fieldset>
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                            <div class="col-sm-6">
                                <input id="email"
                                       class="form-control {{ $errors->has('email') ? 'parsley-error' : '' }}"
                                       name="email" type="email" value="{{old('email', $agent->email)}}"
                                       placeholder="Enter your email address">
                                @if ($errors->has('email'))
                                    <ul class="parsley-errors-list filled">
                                        <li class="parsley-required">{{ $errors->first('email') }}</li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </fieldset>

                    {{--Update Agent--}}
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-6">
                                <button class="btn btn-sm btn-primary" type="submit">Update Agent</button>
                                <a href="{{ route('agents.index') }}" class="btn btn-sm btn-success">Back </a>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection

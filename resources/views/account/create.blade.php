@extends('layouts.app')

@section('title')
    Create SignalWire Account
@endsection

@section('content')

    <div class="content-wrapper">

        {{-- Heading --}}
        <div class="content-heading">
            <div>Create SignalWire Account<small>Create a new SignalWire sub account to handle a business</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
                @include('layouts.flash_messages')

                <div class="card card-default">
                    {{-- <div class="card-header">Create SignalWire Account</div> --}}
                    <div class="card-body">

                        <form class="form-horizontal" method="post" action="{{route('voip-accounts.store')}}">
                            @csrf

                            {{--Friendly Name--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="friendly_name">Friendly Name</label>
                                    <div class="col-sm-6">
                                        <input id="friendly_name" class="form-control {{ $errors->has('friendly_name') ? 'parsley-error' : '' }}" type="text"
                                               name="friendly_name" value="{{ old('friendly_name')}}"
                                               placeholder="Enter account friendly name">
                                        @if ($errors->has('friendly_name'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('friendly_name') }}</li></ul>
                                        @endif


                                    </div>

                                </div>
                            </fieldset>

                            {{--Space URL--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="space_url">Space URL</label>
                                    <div class="col-sm-6">
                                        <input id="space_url" class="form-control {{ $errors->has('space_url') ? 'parsley-error' : '' }}" type="text"
                                               name="space_url" value="{{ old('space_url')}}"
                                               placeholder="Enter account space url">
                                        @if ($errors->has('space_url'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('space_url') }}</li></ul>
                                        @endif

                                    </div>

                                </div>
                            </fieldset>


                            {{--Project ID--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="project_id">Project ID</label>
                                    <div class="col-sm-6">
                                        <input id="project_id" class="form-control {{ $errors->has('project_id') ? 'parsley-error' : '' }}" type="text"
                                               name="project_id" value="{{ old('project_id')}}"
                                               placeholder="Enter Project ID">
                                        @if ($errors->has('project_id'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('project_id') }}</li></ul>
                                        @endif

                                    </div>

                                </div>
                            </fieldset>


                            {{--API Authentication Token--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="api_auth_token">API Authentication Token</label>
                                    <div class="col-sm-6">
                                        <input id="api_auth_token" class="form-control {{ $errors->has('api_auth_token') ? 'parsley-error' : '' }}" type="text"
                                               name="api_auth_token" value="{{ old('api_auth_token')}}"
                                               placeholder="Enter API authentication token">
                                        @if ($errors->has('api_auth_token'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('api_auth_token') }}</li></ul>
                                        @endif

                                    </div>

                                </div>
                            </fieldset>

                            {{--Voice URL--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="voice_url">Voice URL</label>
                                    <div class="col-sm-6">
                                        <input id="voice_url" class="form-control {{ $errors->has('voice_url') ? 'parsley-error' : '' }}" type="text"
                                               placeholder="Optional, it will be auto updated when attached with a business"
                                               name="voice_url" value="{{ old('voice_url')}}"/>
                                        @if ($errors->has('voice_url'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('voice_url') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Voice Fallback URL--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="voice_fallback_url">Voice Fallback URL</label>
                                    <div class="col-sm-6">
                                        <input id="voice_fallback_url" class="form-control {{ $errors->has('voice_fallback_url') ? 'parsley-error' : '' }}" type="text"
                                               placeholder="optional: SignalWire will exe this url in case of failure"
                                               name="voice_fallback_url" value="{{ old('voice_fallback_url')}}"/>
                                        @if ($errors->has('voice_fallback_url'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('voice_fallback_url') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Account Status--}}
                            <fieldset class="last-child">
                                <div class="form-group row">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <select id="status" class="form-control m-b {{ $errors->has('status') ? 'parsley-error' : '' }}" name="status">
                                            <option value="active" @if( old('status') == 'active' ) selected @endif >
                                                Active
                                            </option>
                                            <option value="disable" @if( old('suspended') == 'suspended' ) selected @endif >
                                                Suspend
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('status') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Create Account--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Create Account</button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

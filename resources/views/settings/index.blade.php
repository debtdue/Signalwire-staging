@extends('layouts.app')

@section('title')
    Settings
@endsection

@section('content')

    <div class="content-wrapper">
        {{-- HEADING --}}
        <div class="content-heading">
            <div>Settings<small style="margin-top: 5% !important;">Update API Credentials and General Settings</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
            @include('layouts.flash_messages')

            <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">SignalWire API Credentials</div>
                    <div class="card-body">

                        <form class="form-horizontal" method="post" action="{{route('update_settings')}}">
                            @csrf
                            {{--Space URL--}}
                            <fieldset>
                                <div
                                    class="form-group row{{ $errors->has('signal_wire_space_url') ? ' has-error' : '' }}">
                                    <label for="signal_wire_space_url" class="col-sm-2 col-form-label">Space URL</label>
                                    <div class="col-sm-6">
                                        <input id="signal_wire_space_url" class="form-control {{ $errors->has('signal_wire_space_url') ? 'parsley-error' : '' }}"
                                               name="signal_wire_space_url"
                                               type="text"
                                               value="{{old('signal_wire_space_url', $settings->signal_wire_space_url)}}"
                                               placeholder="Enter your SignalWire authentication token here">

                                        @if ($errors->has('signal_wire_space_url'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('signal_wire_space_url') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Account SID--}}
                            <fieldset>
                                <div
                                    class="form-group row{{ $errors->has('signal_wire_project_id') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="signal_wire_project_id">Project ID</label>
                                    <div class="col-sm-6">
                                        <input id="signal_wire_project_id" class="form-control {{ $errors->has('signal_wire_project_id') ? 'parsley-error' : '' }}" type="text"
                                               name="signal_wire_project_id"
                                               value="{{old('signal_wire_project_id', $settings->signal_wire_project_id)}}"
                                               placeholder="Enter your SignalWire account ssid">

                                        @if ($errors->has('signal_wire_project_id'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('signal_wire_project_id') }}</li></ul>
                                        @endif       
                                    </div>
                                </div>
                            </fieldset>

                            {{--Authentication Token--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('signal_wire_auth_token') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="signal_wire_auth_token">Auth Token</label>
                                    <div class="col-sm-6">
                                        <input id="signal_wire_auth_token" class="form-control {{ $errors->has('signal_wire_auth_token') ? 'parsley-error' : '' }}" type="text"
                                               name="signal_wire_auth_token"
                                               value="{{old('signal_wire_auth_token', $settings->signal_wire_auth_token)}}"
                                               placeholder="Enter your SignalWire account ssid">

                                        @if ($errors->has('signal_wire_auth_token'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('signal_wire_auth_token') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Save Settings--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Save Settings</button>
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

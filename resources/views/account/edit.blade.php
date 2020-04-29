@extends('layouts.app')

@section('title')
    Update SignalWire Account
@endsection

@section('content')

    <div class="content-wrapper">

        {{-- HEADING --}}
        <div class="content-heading">
            <div>Update : {{ucwords($account->title)}}<small>Update account title</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
            @include('layouts.flash_messages')

            <!-- START card-->
                <div class="card card-default">
                    <div class="card-header">Update SignalWire Account</div>
                    <div class="card-body">

                        <form class="form-horizontal" method="post"
                              action="{{route('voip-accounts.update', $account->id)}}">
                            @csrf
                            @method('PUT')


                            {{--Friendly Name--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="friendly_name">Friendly Name</label>
                                    <div class="col-sm-6">
                                        <input id="friendly_name" class="form-control {{ $errors->has('friendly_name') ? 'parsley-error' : '' }}"  type="text"
                                               name="friendly_name" value="{{ $account->friendly_name}}"
                                               placeholder="Enter account friendly name">
                                        @if ($errors->has('friendly_name'))
                                            <span class="help-block m-b-none">{{ $errors->first('friendly_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>


                            {{--Space URL--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="space_url">Space URL</label>
                                    <div class="col-sm-6">
                                        <input id="space_url" class="form-control {{ $errors->has('space_url') ? 'parsley-error' : '' }}"  type="text"
                                               name="space_url" value="{{ $account->space_url}}"
                                               placeholder="Enter account space url">
                                        @if ($errors->has('space_url'))
                                            <span class="help-block m-b-none">{{ $errors->first('space_url') }}</span>
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
                                               name="project_id" value="{{ $account->project_id}}"
                                               placeholder="Enter API authentication token">
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
                                               name="api_auth_token" value="{{ $account->api_auth_token}}"
                                               placeholder="Enter API authentication token">
                                        @if ($errors->has('api_auth_token'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('api_auth_token') }}</li></ul>
                                        @endif

                                    </div>

                                </div>
                            </fieldset>




                            {{--Total Numbers--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="title">Total Numbers</label>
                                    <div class="col-sm-6">
                                        <input id="title" class="form-control {{ $errors->has('title') ? 'parsley-error' : '' }}"  type="text" disabled=""
                                               name="type" value="{{ $account->total_numbers}}"/>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Voice URL--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="voice_url">Voice URL</label>
                                    <div class="col-sm-6">
                                        <input id="voice_url" class="form-control {{ $errors->has('voice_url') ? 'parsley-error' : '' }}"  type="text"
                                               name="voice_url" value="{{ old('voice_url', $account->voice_url ) }}"/>
                                        @if ($errors->has('voice_url'))
                                            <span class="help-block m-b-none">{{ $errors->first('voice_url') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Voice Fallback URL--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="voice_fallback_url">Voice Fallback
                                        URL</label>
                                    <div class="col-sm-6">
                                        <input id="voice_fallback_url" class="form-control {{ $errors->has('voice_fallback_url') ? 'parsley-error' : '' }}"  type="text"
                                               name="voice_fallback_url"
                                               value="{{ old('voice_fallback_url', $account->voice_fallback_url) }}"/>
                                        @if ($errors->has('voice_fallback_url'))
                                            <span
                                                class="help-block m-b-none">{{ $errors->first('voice_fallback_url') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Account Status--}}
                            <fieldset class="last-child">
                                <div class="form-group row{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-6">
                                        <select id="status" class="form-control m-b {{ $errors->has('status') ? 'parsley-error' : '' }}"  name="status">
                                            <option value="active" @if( $account->status == 'active' ) selected @endif >
                                                Active
                                            </option>
                                            <option value="suspended"
                                                    @if( $account->status == 'suspended' ) selected @endif >
                                                Suspend
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="help-block m-b-none">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Update Account--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Update Account</button>
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

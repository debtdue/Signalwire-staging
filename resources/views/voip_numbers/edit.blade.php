@extends('layouts.app')


@section('title')
    Edit Number
@endsection

@section('content')

    <div class="content-wrapper">

        {{-- HEADING --}}
        <div class="content-heading">
            <div>Edit Number {{ $number->friendly_name }}
                <small style="margin-top: 5%;">Update Weh hooks and number properties</small>
            </div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
            @include('layouts.flash_messages')

            <!-- START panel-->
                <div class="card card-default">
                    {{-- <div class="panel-heading">Update an Number</div> --}}
                    <div class="card-body">
                        <form class="form-horizontal" method="post" action="{{route('voip-numbers.update', $number->id)}}">

                            @csrf
                            @method('PUT')

                            {{--Phone Number--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Phone Number</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" type="text" placeholder="Disabled input here..."
                                               value="{{ $number->phone_number }}"
                                               disabled="">
                                    </div>
                                </div>
                            </fieldset>

                            {{--Friendly Name--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('friendly_name') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="friendly_name">Friendly Name</label>
                                    <div class="col-sm-6">
                                        <input id="friendly_name" class="form-control {{ $errors->has('friendly_name') ? 'parsley-error' : '' }}" type="text"
                                               name="friendly_name" value="{{old('friendly_name', $number->friendly_name)}}"
                                               placeholder="Enter friendly name for your phone number">

                                        @if ($errors->has('friendly_name'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('friendly_name') }}</li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--voice url--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('voice_url') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="last_name">Voice URL</label>
                                    <div class="col-sm-6">
                                        <input id="last_name" class="form-control {{ $errors->has('voice_url') ? 'parsley-error' : '' }}" type="text"
                                               name="voice_url" value="{{old('voice_url', $number->voice_url)}}"
                                               placeholder="Enter inbound voice url webhook">

                                        @if ($errors->has('voice_url'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('voice_url') }}</li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Voice Fallback URL--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('voice_fallback_url') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="voice_fallback_url">Voice Fallback URL</label>
                                    <div class="col-sm-6">
                                        <input id="voice_fallback_url" class="form-control {{ $errors->has('voice_fallback_url') ? 'parsley-error' : '' }}" type="text"
                                               name="voice_fallback_url" value="{{old('voice_fallback_url', $number->voice_fallback_url)}}"
                                               placeholder="Enter inbound voice fallback url webhook">

                                        @if ($errors->has('voice_fallback_url'))
                                        <ul class="parsley-errors-list filled">
                                            <li class="parsley-required">{{ $errors->first('voice_fallback_url') }}</li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Assigned Business--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Assigned Business</label>
                                    <div class="col-lg-6">
                                        <input class="form-control" type="text" placeholder="Disabled input here..."
                                               value="@if( isset($number->account->business->title )) {{ $number->account->business->title }} @endif"
                                               disabled="">
                                    </div>
                                </div>
                            </fieldset>

                            {{--Udpate Number--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Update Number</button>
                                        <a href="{{ route('voip-numbers.show',$number->voip_account_id) }}" class="btn btn-sm btn-success" >Back </a>
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

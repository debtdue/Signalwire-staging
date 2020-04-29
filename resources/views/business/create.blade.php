@extends('layouts.app')

@section('title')
    Add Business
@endsection

@section('page_styles')
    <!-- CHOSEN-->
    <link rel="stylesheet" href="{{ asset('vendor/chosen-js/chosen.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">

        {{-- HEADING --}}
        <div class="content-heading">
            <div>Add Business<small style="margin-top: 5%;">Add a new business</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
            @include('layouts.flash_messages')

                <div class="card card-default">
                    <div class="card-body">
                        <form class="form-horizontal" method="post" action="{{ route('businesses.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{--Title--}}
                            <fieldset>
                                <legend class="font-weight-normal">General Inputs</legend>
                                <div class="form-group row{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="title">Business Title</label>
                                    <div class="col-sm-6">
                                        <input id="title" class="form-control {{ $errors->has('title') ? 'parsley-error' : '' }}" type="text" name="title" value="{{ old('title')}}" placeholder="Enter business title">

                                        @if ($errors->has('title'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('title') }}</li></ul>
                                        @endif

                                    </div>
                                </div>
                            </fieldset>

                            {{--Business Description--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="description">Description</label>
                                    <div class="col-sm-6">
                                        <input id="description" class="form-control {{ $errors->has('description') ? 'parsley-error' : '' }}" type="text" name="description" value="{{ old('description')}}" placeholder="Enter business description">

                                        @if ($errors->has('description'))
                                            <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('description') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Call Duration--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('call_duration') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="call_duration">Call Duration</label>
                                    <div class="col-sm-6">
                                        <input id="call_duration" class="form-control {{ $errors->has('call_duration') ? 'parsley-error' : '' }}" type="number" name="call_duration" value="{{ old('call_duration')}}"
                                            placeholder="Enter allowed call duration">

                                        @if ($errors->has('call_duration'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('call_duration') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Assign Agetns--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('assign_agents') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="assign_agents">Assign Agents to Forward them Calls</label>
                                    <div class="col-sm-6">
                                        <select id="assign_agents" name="assign_agents[]" class="chosen-select form-control {{ $errors->has('assign_agents') ? 'parsley-error' : '' }}" data-placeholder="Type agents name to search" multiple>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}">{{ $agent->first_name . ' ' . $agent->last_name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('assign_agents'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('assign_agents') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Assigned Twillio Account--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('voip_account_id') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label" for="assign_agents">Assign VoIP Numbers Account</label>
                                    <div class="col-sm-6">
                                        <select class="chosen-select input-md form-control {{ $errors->has('voip_account_id') ? 'parsley-error' : '' }}" name="voip_account_id"
                                                data-placeholder="Type account name to search" id="assign_agents" >
                                            <option value=""></option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->friendly_name }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('voip_account_id'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('voip_account_id') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Display Caller's Number--}}
                            <fieldset>
                                <div class="form-group row{{ $errors->has('display_caller_number') ? ' has-error' : '' }}">
                                    <label for="display_caller_number" class="col-sm-2 col-form-label">Display Caller's
                                        Numbers</label>
                                    <div class="col-sm-6">
                                        <div class="checkbox c-checkbox needsclick">
                                            <label class="needsclick">
                                                <input class="needsclick {{ $errors->has('display_caller_number') ? 'parsley-error' : '' }}" name="display_caller_number" type="checkbox"
                                                    value="1" id="display_caller_number">
                                                <span class="fa fa-check"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Email Address--}}
                            <fieldset class="last-child">
                                <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                                    <div class="col-sm-6">
                                        <input id="email" class="form-control {{ $errors->has('email') ? 'parsley-error' : '' }}" name="email" type="email"
                                            value="{{old('email')}}"
                                            placeholder="Enter your email address">

                                        @if ($errors->has('email'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('email') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting Message Type--}}
                            <fieldset>
                                <legend class="font-weight-normal">Greeting Block</legend>
                                <div class="form-group row{{ $errors->has('greeting_message_type') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label">Greeting Message Type</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('greeting_message_type') ? 'parsley-error' : '' }}" name="greeting_message_type" value="text_to_speak"
                                                    @if( old('greeting_message_type') != "text_to_speak"
                                                    and old('greeting_message_type') != "greeting_audio") checked=""
                                                    @elseif( old('greeting_message_type') == "text_to_speak") checked=""
                                                    @endif onclick="showGreetingMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Text to Speak</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('greeting_message_type') ? 'parsley-error' : '' }}" name="greeting_message_type" value="mp3_audio"
                                                    {{ old('greeting_message_type') == "mp3_audio" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                    onclick="showGreetingMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Mp3 Audio
                                            </label>
                                        </div>

                                        @if ($errors->has('greeting_message_type'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('greeting_message_type') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting Audio--}}
                            <fieldset style="display: none" id="greeting_audio_block">
                                <div class="form-group row{{ $errors->has('greeting_audio') ? ' has-error' : '' }}">
                                    <label for="greeting_audio" class="col-sm-2 col-form-label">Greeting Audio
                                        File</label>
                                    <div class="col-sm-6">
                                        <input class="form-control filestyle {{ $errors->has('greeting_audio') ? 'parsley-error' : '' }}" type="file"
                                            data-classbutton="btn btn-default"
                                            data-classinput="form-control inline" name="greeting_audio"
                                            id="greeting_audio">

                                        @if ($errors->has('greeting_audio'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('greeting_audio') }}</li></ul>
                                        @endif

                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting Text to Speak--}}
                            <fieldset style="display: none" id="greeting_text_block" class="last-child">
                                <div class="form-group row{{ $errors->has('greeting_text') ? ' has-error' : '' }}">
                                    <label for="greeting_text" class="col-sm-2 col-form-label">Greeting Text to
                                        Speak</label>
                                    <div class="col-sm-6">
                                        <textarea id="greeting_text" class="form-control {{ $errors->has('greeting_text') ? 'parsley-error' : '' }}" name="greeting_text" type="text" placeholder="Enter text here which will be played to caller">{{old('greeting_text')}}</textarea>

                                        @if ($errors->has('greeting_text'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('greeting_text') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Turn Whisper Message On or Off--}}
                            <fieldset>
                                <legend class="font-weight-normal">Whispering Block</legend>
                                <div class="form-group row{{ $errors->has('whisper_message') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label">Turn Whisper Message</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('whisper_message') ? 'parsley-error' : '' }}" name="whisper_message" value="on"
                                                    @if( old('whisper_message') != "on" and old('whisper_message') != "off")
                                                            checked=""
                                                    @elseif( old('whisper_message') == "on")
                                                        checked=""
                                                    @endif
                                                    onclick="showWhisperMessageOptions(this.value);" >
                                                <span class="fa fa-circle"></span>ON</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('whisper_message') ? 'parsley-error' : '' }}" name="whisper_message" value="off"
                                                    {{ old('whisper_message') == "off" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                    onclick="showWhisperMessageOptions(this.value);" >
                                                <span class="fa fa-circle"></span>OFF</label>
                                        </div>

                                        @if ($errors->has('whisper_message'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('whisper_message') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Whisper Message Types--}}
                            <fieldset id="whisper_message_type_block">
                                <div class="form-group row{{ $errors->has('whisper_message_type') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label">Whisper Message Type</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('whisper_message_type') ? 'parsley-error' : '' }}" name="whisper_message_type" value="text_to_speak"
                                                    @if( old('whisper_message_type') != "text_to_speak" and old('whisper_message_type') != "mp3_audio")
                                                    checked=""
                                                    @elseif( old('whisper_message_type') == "text_to_speak")
                                                    checked=""
                                                    @endif
                                                    onclick="showWhisperMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Text to Speak
                                            </label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('whisper_message_type') ? 'parsley-error' : '' }}" name="whisper_message_type" value="mp3_audio"
                                                    {{ old('whisper_message_type') == "mp3_audio" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                    onclick="showWhisperMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Mp3 Audio
                                            </label>
                                        </div>

                                        @if ($errors->has('whisper_message_type'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('whisper_message_type') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Whisper Message Audio--}}
                            <fieldset style="display: none" id="whisper_message_audio_block">
                                <div class="form-group row{{ $errors->has('whisper_message_audio') ? ' has-error' : '' }}">
                                    <label for="whisper_message_audio" class="col-sm-2 col-form-label">Whisper Message Audio File</label>
                                    <div class="col-sm-6">
                                        <input class="form-control {{ $errors->has('whisper_message_audio') ? 'parsley-error' : '' }} filestyle" type="file"
                                            data-classbutton="btn btn-default"
                                            data-classinput="form-control inline" name="whisper_message_audio"
                                            id="whisper_message_audio">

                                        @if ($errors->has('whisper_message_audio'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('whisper_message_audio') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Whisper Greeting Text to Speak--}}
                            <fieldset style="display: none" id="whisper_message_text_block" class="last-child">
                                <div class="form-group row{{ $errors->has('whisper_message_text') ? ' has-error' : '' }}">
                                    <label for="whisper_message_text" class="col-sm-2 col-form-label">Whisper Message
                                        Text to Speak</label>
                                    <div class="col-sm-6">
                                        <textarea id="whisper_message_text" class="form-control {{ $errors->has('whisper_message_text') ? 'parsley-error' : '' }}"
                                                name="whisper_message_text" type="text"
                                                placeholder="Enter text here which will be played to caller">{{old('whisper_message_text')}}</textarea>

                                        @if ($errors->has('whisper_message_text'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('whisper_message_text') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Record Calls--}}
                            {{--<fieldset class="last-child">--}}
                                {{--<div class="form-group row{{ $errors->has('record_calls') ? ' has-error' : '' }}">--}}
                                    {{--<label for="record_calls" class="col-sm-2 col-form-label">Record Calls</label>--}}
                                    {{--<div class="col-sm-6">--}}
                                        {{--<div class="checkbox c-checkbox needsclick">--}}
                                            {{--<label class="needsclick">--}}
                                                {{--<input id="record_calls" name="record_calls" class="needsclick"--}}
                                                    {{--type="checkbox" value="1">--}}
                                                {{--<span class="fa fa-check"></span></label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</fieldset>--}}

                            {{--Turn Recording ON | OFF--}}
                            <fieldset>
                                <legend class="font-weight-normal">Recording Block</legend>

                                <div class="form-group row{{ $errors->has('record_calls') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label">Turn Calls Recording</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('record_calls') ? 'parsley-error' : '' }}" name="record_calls" value="on"
                                                    @if( old('record_calls') != "on" and old('record_calls') != "off")
                                                    checked=""
                                                    @elseif( old('record_calls') == "on")
                                                    checked=""
                                                    @endif
                                                    onclick="showRecordingDisclaimerOption(this.value);" >
                                                <span class="fa fa-circle"></span>ON</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('record_calls') ? 'parsley-error' : '' }}" name="record_calls" value="off"
                                                    {{ old('record_calls') == "off" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                    onclick="showRecordingDisclaimerOption(this.value);" >
                                                <span class="fa fa-circle"></span>OFF</label>
                                        </div>

                                        @if ($errors->has('record_calls'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('record_calls') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Recording Disclaimer Types--}}
                            <fieldset id="recording_disclaimer_type_block">
                                <div class="form-group row{{ $errors->has('recording_disclaimer_type') ? ' has-error' : '' }}">
                                    <label class="col-sm-2 col-form-label">Recording Disclaimer Type</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('recording_disclaimer_type') ? 'parsley-error' : '' }}" name="recording_disclaimer_type" value="text_to_speak"
                                                    @if( old('recording_disclaimer_type') != "text_to_speak" and
                                                    old('recording_disclaimer_type') != "mp3_audio" )
                                                    checked=""
                                                    @elseif( old('recording_disclaimer_type') == "text_to_speak")
                                                    checked=""
                                                    @endif
                                                    onclick="showRecordingDisclaimerFields(this.value,'on');">
                                                <span class="fa fa-circle"></span>Text to Speak</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" class="{{ $errors->has('recording_disclaimer_type') ? 'parsley-error' : '' }}" name="recording_disclaimer_type" value="mp3_audio"
                                                    {{ old('recording_disclaimer_type') == "mp3_audio" ? 'checked='.'"'.'checked'.'"' : '' }}
                                                    onclick="showRecordingDisclaimerFields(this.value,'on');">
                                                <span class="fa fa-circle"></span>Mp3 Audio</label>
                                        </div>

                                        @if ($errors->has('recording_disclaimer_type'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('recording_disclaimer_type') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Recording Disclaimer Audio File--}}
                            <fieldset style="display: none" id="recording_disclaimer_audio_block">
                                <div class="form-group row{{ $errors->has('recording_disclaimer_audio') ? ' has-error' : '' }}">
                                    <label for="recording_disclaimer_audio" class="col-sm-2 col-form-label">Disclaimer Audio File</label>
                                    <div class="col-sm-6">
                                        <input class="form-control filestyle {{ $errors->has('recording_disclaimer_audio') ? 'parsley-error' : '' }}" type="file"
                                            data-classbutton="btn btn-default"
                                            data-classinput="form-control inline" name="recording_disclaimer_audio"
                                            id="recording_disclaimer_audio">

                                        @if ($errors->has('recording_disclaimer_audio'))
                                        <ul class="parsley-errors-list filled" >
                                            <li class="parsley-required">{{ $errors->first('recording_disclaimer_audio') }}</li>
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Recording DisclaimerText to Speak--}}
                            <fieldset style="display: none" id="recording_disclaimer_text_block">
                                <div class="form-group row{{ $errors->has('recording_disclaimer_text') ? ' has-error' : '' }}">
                                    <label for="recording_disclaimer_text" class="col-sm-2 col-form-label">Disclaimer Text to Speak</label>
                                    <div class="col-sm-6">
                                        <textarea id="recording_disclaimer_text" class="form-control {{ $errors->has('recording_disclaimer_text') ? 'parsley-error' : '' }}"
                                                name="recording_disclaimer_text" type="text"
                                                placeholder="Enter text here which will be played to caller">{{old('recording_disclaimer_text')}}</textarea>

                                        @if ($errors->has('recording_disclaimer_text'))
                                        <ul class="parsley-errors-list filled" ><li class="parsley-required">{{ $errors->first('recording_disclaimer_text') }}</li></ul>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Add business--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <button class="btn btn-sm btn-primary" type="submit">Add Business</button>
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

@section('page_script')
    <!-- CHOSEN-->
    <script src="{{ asset('vendor/chosen-js/chosen.jquery.js') }}"></script>

    <!-- FILESTYLE-->
    <script src="{{ asset('vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script>

    @include('business.scripts')
@endsection

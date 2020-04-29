@extends('layouts.app')

@section('title')
    View Business
@endsection

@section('page_styles')
    <!-- CHOSEN-->
    <link rel="stylesheet" href="{{ asset('vendor/chosen-js/chosen.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        {{-- Heading --}}
        <div class="content-heading">
            <div>Business: {{ $business->title }}<small>View business flow</small></div>
        </div>

        <!-- START widgets box-->
        <div class="row">
            <div class="col-lg-12">
                @include('layouts.flash_messages')

                <!-- START card-->
                <div class="card card-default">
                    {{-- <div class="card-header"> Form elements</div> --}}
                    <div class="card-body">

                        <form class="form-horizontal">

                            {{--Title--}}
                            <fieldset>
                                <legend>General Inputs</legend>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="title">Business Title</label>
                                    <div class="col-sm-6">
                                        <input id="title" class="form-control" type="text" disabled
                                               name="title" value="{{ $business->title }}"
                                               placeholder="Enter business title">
                                    </div>
                                </div>
                            </fieldset>

                            {{--Business Description--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="description">Description</label>
                                    <div class="col-sm-6">
                                        <input id="description" class="form-control" type="text" disabled
                                               name="description" value="{{ $business->description }}"
                                               placeholder="Enter business description">
                                    </div>
                                </div>
                            </fieldset>

                            {{--Call Duration--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="call_duration">Call Duration</label>
                                    <div class="col-sm-6">
                                        <input id="call_duration" class="form-control" type="text" name="call_duration"
                                               value="{{ $business->call_duration }}" disabled
                                               placeholder="Enter call duration limit">
                                    </div>
                                </div>
                            </fieldset>

                            {{--Assign Agetns--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="assign_agents">Assign Agents to Forward
                                        them Calls</label>
                                    <div class="col-sm-6">
                                        <select id="assign_agents" name="assign_agents[]" readonly
                                                class="chosen-select form-control" data-option-array-index="2"
                                                data-placeholder="Type agents name to search" multiple>
                                            @foreach($agents as $agent)
                                                <option selected>{{ $agent->first_name . ' ' . $agent->last_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Assigned Twillio Account--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="voip_account_id">Assign VoIP Numbers
                                        Account
                                    </label>
                                    <div class="col-sm-6">
                                        <select class="chosen-select input-md form-control" name="voip_account_id"
                                                id="voip_account_id" readonly
                                                data-placeholder="Type account name to search">
                                            <option value="{{$business->account->id}}">{{$business->account->friendly_name}}</option>

                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Display Caller's Number--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label for="display_caller_number" class="col-sm-2 col-form-label">Display Caller's
                                        Numbers</label>
                                    <div class="col-sm-6">
                                        <div class="checkbox c-checkbox needsclick">
                                            <label class="needsclick">
                                                <input class="needsclick" name="display_caller_number" type="checkbox"
                                                       @if( $business->display_caller_number == "1" ) checked="" readonly
                                                       @endif
                                                       value="1" id="display_caller_number">
                                                <span class="fa fa-check"></span></label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Email Address--}}
                            <fieldset class="last-child">
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email Address</label>
                                    <div class="col-sm-6">
                                        <input id="email" class="form-control" name="email" type="email"
                                               value="{{ $business->email }}" readonly
                                               placeholder="Enter your email address">
                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting Message Type--}}
                            <fieldset>
                                <legend>Greeting Block</legend>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Greeting Message Type</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="greeting_message_type" value="text_to_speak"
                                                       @if( $business->greeting_message_type == "text_to_speak" )checked=""
                                                       @endif readonly
                                                       onclick="showGreetingMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Text to Speak</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="greeting_message_type" value="mp3_audio"
                                                       @if( $business->greeting_message_type == "mp3_audio" )checked=""
                                                       @endif readonly
                                                       onclick="showGreetingMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Mp3 Audio</label>

                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting Audio--}}
                            <fieldset style="display: none" id="greeting_audio_block" class="last-child">
                                <div class="form-group row">
                                    <label for="greeting_audio" class="col-sm-2 col-form-label">Greeting Audio
                                        File</label>
                                    <div class="col-sm-6">
                                        @if(!is_null($business->greeting_audio ) )
                                            <audio controls>
                                                <source src="{{asset('storage/'.$business->id.'/greetings/greeting.mp3')}}"
                                                        type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Greeting Text to Speak--}}
                            <fieldset style="display: none" id="greeting_text_block" class="last-child">
                                <div class="form-group row">
                                    <label for="greeting_text" class="col-sm-2 col-form-label">Greeting Text to
                                        Speak</label>
                                    <div class="col-sm-6">
                                        <textarea id="greeting_text" class="form-control" name="greeting_text"
                                                  type="text" readonly
                                                  placeholder="Enter text here which will be played to caller">{{ $business->greeting_text }}</textarea>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Turn Whisper Message On or Off--}}
                            <fieldset>
                                <legend>Whispering Block</legend>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Turn Whisper Message </label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="whisper_message" value="on"

                                                       @if( $business->whisper_message == "1" ) checked=""
                                                       @endif
                                                       onclick="showWhisperMessageOptions(this.value);">
                                                <span class="fa fa-circle"></span>ON</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="whisper_message" value="off"
                                                       @if( $business->whisper_message == "0") checked=""
                                                       @endif

                                                       onclick="showWhisperMessageOptions(this.value);">
                                                <span class="fa fa-circle"></span>OFF</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Whisper Message Types--}}
                            <fieldset id="whisper_message_type_block">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Whisper Message Type</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="whisper_message_type" value="text_to_speak"
                                                       @if( $business->whisper_message_type == "text_to_speak" ) checked=""
                                                       @endif
                                                       onclick="showWhisperMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Text to Speak</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="whisper_message_type" value="mp3_audio"

                                                       @if( $business->whisper_message_type == "mp3_audio" ) checked=""
                                                       @endif
                                                       onclick="showWhisperMsgOptions(this.value);">
                                                <span class="fa fa-circle"></span>Mp3 Audio</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Whisper Message Audio--}}
                            <fieldset style="display: none" id="whisper_message_audio_block" class="last-child">
                                <div class="form-group row">
                                    <label for="whisper_message_audio" class="col-sm-2 col-form-label">Whisper Message Audio File</label>
                                    <div class="col-sm-6">
                                        @if($business->whisper_message == 1 and !is_null($business->whisper_message_audio ) )
                                            <audio controls>
                                                <source src="{{asset('storage/'.$business->id.'/whisper_audio/whisper_message.mp3')}}"
                                                        type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                            {{--Whisper Text to Speak--}}
                            <fieldset style="display: none" id="whisper_message_text_block" class="last-child">
                                <div class="form-group row">
                                    <label for="whisper_message_text" class="col-sm-2 col-form-label">Whisper Message
                                        Text to Speak</label>
                                    <div class="col-sm-6">
                                        <textarea id="whisper_message_text" class="form-control"
                                                  name="whisper_message_text" readonly type="text"
                                                  placeholder="Enter text here which will be played to caller">{{ $business->whisper_message_text }}</textarea>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Turn Recording ON | OFF--}}
                            <fieldset>
                                <legend>Recording Block</legend>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Turn Calls Recording</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="record_calls" value="on"
                                                       @if( $business->record_calls == 1 ) checked=""
                                                       @endif
                                                       onclick="showRecordingDisclaimerOption(this.value);">
                                                <span class="fa fa-circle"></span>ON</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="record_calls" value="off"
                                                       @if( $business->record_calls == 0 ) checked="" @endif
                                                       onclick="showRecordingDisclaimerOption(this.value);">
                                                <span class="fa fa-circle"></span>OFF</label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Recording Disclaimer Types--}}
                            <fieldset id="recording_disclaimer_type_block">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Recording Disclaimer Type</label>
                                    <div class="col-sm-6">
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="recording_disclaimer_type"
                                                       value="text_to_speak"
                                                       @if( $business->recording_disclaimer_type == "text_to_speak" ) checked=""
                                                       @endif
                                                       onclick="showRecordingDisclaimerFields(this.value,'on');">
                                                <span class="fa fa-circle"></span>Text to Speak</label>
                                        </div>
                                        <div class="radio c-radio">
                                            <label>
                                                <input type="radio" name="recording_disclaimer_type" value="mp3_audio"
                                                       @if( $business->recording_disclaimer_type == "mp3_audio" ) checked=""
                                                       @endif
                                                       onclick="showRecordingDisclaimerFields(this.value,'on');">
                                                <span class="fa fa-circle"></span>Mp3 Audio</label>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>

                            {{--Recording Disclaimer Audio File--}}
                            <fieldset style="display: none" id="recording_disclaimer_audio_block">
                                <div class="form-group row">
                                    <label for="recording_disclaimer_audio" class="col-sm-2 col-form-label">Disclaimer Audio File</label>
                                    <div class="col-sm-6">
                                        @if($business->record_calls == 1 and !is_null($business->recording_disclaimer_audio ) )
                                            <audio controls>
                                                <source src="{{asset('storage/'.$business->id.'/recording_disclaimer_audio/recording_disclaimer.mp3')}}"
                                                        type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @endif

                                    </div>
                                </div>
                            </fieldset>

                            {{--Recording DisclaimerText to Speak--}}
                            <fieldset style="display: none" id="recording_disclaimer_text_block">
                                <div class="form-group row">
                                    <label for="recording_disclaimer_text" class="col-sm-2 col-form-label">Disclaimer
                                        Text to Speak</label>
                                    <div class="col-sm-6">
                                        <textarea id="recording_disclaimer_text" class="form-control"
                                                  name="recording_disclaimer_text" readonly type="text"
                                                  placeholder="Enter text here which will be played to caller">{{ $business->recording_disclaimer_text }}</textarea>
                                    </div>
                                </div>
                            </fieldset>

                            {{--Back To Businesses--}}
                            <fieldset>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label"></label>
                                    <div class="col-sm-6">
                                        <a href="{{route('businesses.index')}}" class="btn btn-sm btn-success">Back to Businesses</a>
                                    </div>
                                </div>
                            </fieldset>

                        </form>

                    </div>
                </div>
                <!-- END card-->

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

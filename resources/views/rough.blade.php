    {{-- OLD CODE --}}
    <div class="content-wrapper">
        <div class="content-heading">
            Dashboard
            <small data-localize="dashboard.WELCOME"></small>
        </div>
    
        <!-- START Statistics widgets boxes-->
        @include('dashboard.counts')
        <!-- END widgets box-->
    
        <div class="row">
    
            <!-- START dashboard main content-->
            @include('dashboard.content')
            <!-- END dashboard main content-->
    
            <!-- START dashboard sidebar-->
            @include('dashboard.sidebar')
            <!-- END dashboard sidebar-->
    
        </div>
    </div>


    {{-- FOR TEXT --}}

    "_token": "7ruEFcAt74znryrhHfGEp01ofxRZjNedmFfv2Xss",

    "title": "1",

    "description": "2",

    "call_duration": "3",

    "assign_agents": ["1"],

    "voip_account_id": null,

    "display_caller_number": "1",

    "email": "test@test.com",

    "greeting_message_type": "text_to_speak",

    "greeting_text": "Greeting Message",

    "whisper_message": "on",

    "whisper_message_type": "text_to_speak",

    "whisper_message_text": "Whisper Message",

    "record_calls": "on",

    "recording_disclaimer_type": "text_to_speak",
    
    "recording_disclaimer_text": "Recording Disclaimer"




{{-- FOR FILE --}}
{
    "_token": "7ruEFcAt74znryrhHfGEp01ofxRZjNedmFfv2Xss",

    "title": "title",

    "description": "dexcription",

    "call_duration": "25",

    "assign_agents": ["2"],

    "voip_account_id": null,

    "display_caller_number": "1",

    "email": "test@test.com",

    "greeting_message_type": "mp3_audio",

    "greeting_text": null,

    "whisper_message": "on",

    "whisper_message_type": "mp3_audio",

    "whisper_message_text": null,

    "record_calls": "on",

    "recording_disclaimer_type": "mp3_audio",

    "recording_disclaimer_text": null,

    "greeting_audio": {},

    "whisper_message_audio": {},

    "recording_disclaimer_audio": {}

}





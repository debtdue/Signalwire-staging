<script>

    (function (window, document, $, undefined) {

        $(function () {

            // CHOSEN
            // -----------------------------------
            $('.chosen-select').chosen();

            // Show, hide greeting messages type block
            let greetingMsgTypeValue = $('input[name=greeting_message_type]:checked').val();
            showGreetingMsgOptions(greetingMsgTypeValue);


            // Show, hide greeting messages type block
            let whisperMsgTypeValue = $('input[name=whisper_message_type]:checked').val();
            showWhisperMsgOptions(whisperMsgTypeValue);


            // Show, hide whisper messages type block
            let whisperMsgOptionValue = $('input[name=whisper_message]:checked').val();
            showWhisperMessageOptions(whisperMsgOptionValue);


            let recordingDisclaimerOptionValue = $('input[name=record_calls]:checked').val();
            showRecordingDisclaimerOption(recordingDisclaimerOptionValue);


            let recordingDisclaimerTypeValue = $('input[name=recording_disclaimer_type]:checked').val();
            showRecordingDisclaimerFields(recordingDisclaimerTypeValue,recordingDisclaimerOptionValue);




        });

    })(window, document, window.jQuery);


    /**
     * Show and hide whisper messages options
     *
     * @param value
     */
    function showWhisperMessageOptions(value) {

        if( value === 'off'){
            // hide whole block
            $('#whisper_message_type_block').hide('slow');
            $('#whisper_message_text_block').hide('slow');
            $('#whisper_message_audio_block').hide('slow');
        }else{
            $('#whisper_message_type_block').show('slow');

            let whisperMsgTypeValue1 = $('input[name=whisper_message_type]:checked').val();
            showWhisperMsgOptions(whisperMsgTypeValue1);
        }

    }

    /**
     * Show and hide greeting messages options
     *
     * @param option
     */
    function showGreetingMsgOptions(option) {

        // console.log(value);
        if (option === 'text_to_speak') {

            $('#greeting_text_block').show('slow');
            $('#greeting_audio_block').hide('slow');
        } else {
            $('#greeting_text_block').hide('slow');
            $('#greeting_audio_block').show('slow');

        }
    }

    /**
     * Show and hide whisper messages options
     *
     * @param option
     */
    function showWhisperMsgOptions(option) {
        // alert(option);
        if (option === 'text_to_speak') {

            $('#whisper_message_text_block').show('slow');
            $('#whisper_message_audio_block').hide('slow');
        } else {
            $('#whisper_message_text_block').hide('slow');
            $('#whisper_message_audio_block').show('slow');

        }
    }



    /**
     * Show and hide recording disclaimer methods
     *
     * @param value
     */
    function showRecordingDisclaimerOption(value) {

        // alert(value);return false;
        if( value === 'off'){
            // hide whole block
            $('#recording_disclaimer_type_block').hide('slow');
            $('#recording_disclaimer_audio_block').hide('slow');
            $('#recording_disclaimer_text_block').hide('slow');
        }else{
            $('#recording_disclaimer_type_block').show('slow');

            let recordingDisclaimerTypeValue1 = $('input[name=recording_disclaimer_type]:checked').val();
            let isRecord = $('input[name=record_calls]:checked').val();
            showRecordingDisclaimerFields(recordingDisclaimerTypeValue1,isRecord);
        }

    }


    function showRecordingDisclaimerFields(option,isRecord) {
        // alert('Recording is => ' + isRecord + ' option is =>' + option);
        if (option === 'text_to_speak' && isRecord === 'on') {

            $('#recording_disclaimer_text_block').show('slow');
            $('#recording_disclaimer_audio_block').hide('slow');
        }
        else if (option === 'mp3_audio' && isRecord === 'on') {
            $('#recording_disclaimer_text_block').hide('slow');
            $('#recording_disclaimer_audio_block').show('slow');
        }

    }
</script>
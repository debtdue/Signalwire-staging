{{--Custom Search Input--}}
<input class="form-control mb" type="text" name="custom_search_input"
       placeholder="Search To or From number, location, direction, etc."
       @if( ! is_null(request('custom_search_input'))) value="{{ request('custom_search_input') }}"
       @else value="" @endif
>
<br>
<div class="clearfix">

    <button class="float-left btn btn-primary" type="submit">Search</button>
    <div class="float-right">
        <label class="checkbox-inline c-checkbox">

        {{-- Hide Spam calls--}}
        <input id="inlineCheckbox10" type="checkbox" @if( ! is_null(request('hide_spam_call'))) checked @endif value="yes" name="hide_spam_call">
        <span class="fa fa-check"></span>Hide Spam Calls</label>

        {{--Show voice mails--}}
        <label class="checkbox-inline c-checkbox">
        <input id="inlineCheckbox20" type="checkbox" value="yes" @if( ! is_null(request('show_voice_mails'))) checked @endif name="show_voice_mails">
        <span class="fa fa-check"></span>show VoiceMails</label>

        {{--Show completed Calls--}}
        <label class="checkbox-inline c-checkbox">
        <input id="inlineCheckbox30" type="checkbox" value="yes" @if( ! is_null(request('show_completed_calls_only'))) checked @endif name="show_completed_calls_only">
        <span class="fa fa-check"></span>Show Completed Calls Only</label>
    </div>
</div>

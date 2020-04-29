<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Business;
use App\Http\SourceProviders\SignalWire;
use App\VoipAccount;
use App\VoipNumber;
use DateTime;
use DateTimeZone;
use DeepCopy\f001\B;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $businesses = Business::with( 'agents' )->orderBy( 'title', 'asc' )->get();
        $counter    = 0;

        return view( 'business.index', compact( 'businesses', 'counter' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $agents   = Agent::all();
        $accounts = VoipAccount::where( 'business_id', null )->where( 'status', 'active' )->get();

        return view( 'business.create', compact( 'agents', 'accounts' ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //return $request->all();

        $whisperMessage      = ( $request->whisper_message == 'on' ) ? 1 : 0;
        $recordcalls         = ( $request->record_calls == 'off' ) ? 0 : 1;
        $displaycallernumber = ( ! isset( $request->display_caller_number ) ) ? 0 : $request->display_caller_number;

        $rules = [
            'voip_account_id'       => 'required',
            'description'           => 'required',
            'call_duration'         => 'nullable|numeric',
            'email'                 => 'nullable|email|max:100',
            'assign_agents'         => 'required',
            'greeting_message_type' => 'required', // text or file
            'whisper_message'       => 'required', // on or off
            'record_calls'          => 'required', // on or off
            'title'                 => 'required|max:100',
        ];

        // rules for greeting message types
        if ( $request->greeting_message_type == 'mp3_audio' ) :
            //$rules[ 'greeting_audio' ] = 'required|file|mimetypes:audio/mpeg,wav';

        else:
            $rules[ 'greeting_text' ] = 'required';
        endif;

        // rules for whisper message
        if ( $request->whisper_message == 'on' ) {

            $rules[ 'whisper_message_type' ] = 'required';

            if ( $request->whisper_message_type == 'mp3_audio' ) :
                //$rules[ 'whisper_message_audio' ] = 'required|file|mimetypes:audio/mpeg,wav';
            else:
                $rules[ 'whisper_message_text' ] = 'required';
            endif;
        }

        // rules for whisper message
        if ( $request->record_calls == 'on' ) {

            $rules[ 'recording_disclaimer_type' ] = 'required';

            if ( $request->recording_disclaimer_type == 'mp3_audio' ) :
                //$rules[ 'recording_disclaimer_audio' ] = 'required|file|mimetypes:audio/mpeg,wav';
            else:
                $rules[ 'recording_disclaimer_text' ] = 'required';
            endif;
        }

        // validation
        $request->validate( $rules );

        // get the next insert id of this model
        $nextId = ( new Business() )->getNextInsertID();

        /// upload a  greeting file
        $greetingFilepath            = null;
        $whisperFilepath             = null;
        $recordingDisclaimerFilePath = null;
        if ( $request->hasfile( 'greeting_audio' ) ) {
            $greetingFilepath = $this->uploadAudio( 'greeting_audio', "public/" . $nextId . "/greetings", 'greeting.mp3' );
            $greetingFilepath = 'greeting.mp3';
        }

        // upload whisper audio
        if ( $request->hasfile( 'whisper_message_audio' ) ) {
            $whisperFilepath = $this->uploadAudio( 'whisper_message_audio', "public/" . $nextId . "/whisper_audio", 'whisper_message.mp3' );
            $whisperFilepath = 'whisper_message.mp3';
        }

        // upload a recording disclaimer audio
        if ( $request->hasfile( 'recording_disclaimer_audio' ) ) {
            $this->uploadAudio( 'recording_disclaimer_audio', "public/" . $nextId . "/recording_disclaimer_audio", 'recording_disclaimer.mp3' );
            $recordingDisclaimerFilePath = 'recording_disclaimer.mp3';
        }

        // Save
        $business                             = new Business();
        $business->user_id                    = auth()->id();
        $business->voip_account_id            = $request->voip_account_id;
        $business->title                      = $request->title;
        $business->description                = $request->description;
        $business->call_duration              = date( 'H:i:s', $request->call_duration );
        $business->email                      = $request->email;
        $business->greeting_message_type      = $request->greeting_message_type;
        $business->greeting_text              = $request->greeting_text;
        $business->greeting_audio             = $greetingFilepath;
        $business->whisper_message            = $whisperMessage;
        $business->whisper_message_type       = $request->whisper_message_type;
        $business->whisper_message_text       = $request->whisper_message_text;
        $business->whisper_message_audio      = $whisperFilepath;
        $business->record_calls               = $recordcalls;
        $business->recording_disclaimer_type  = $request->recording_disclaimer_type;
        $business->recording_disclaimer_text  = $request->recording_disclaimer_text;
        $business->recording_disclaimer_audio = $recordingDisclaimerFilePath;

        $business->display_caller_number = $displaycallernumber;

        $business->save();

        // insert into pivot table
        $business->agents()->attach( $request->assign_agents, [ 'created_at' => now(), 'updated_at' => now() ] );

        // Add business to Account model and update numbers urls on SignalWire
        $this->updateAccountNumbers( $request, $business->id );

        // set success message
        $request->session()->flash( 'alert-success', 'Business has been created successfully!' );

        // redirect back
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show( $id )
    {
        $business = Business::where( 'id', $id )->with( 'agents' )->first();
        $agents   = $business->agents;

        return view( 'business.show', compact( 'business', 'agents' ) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Exception
     */
    public function edit( $id )
    {
        $business       = Business::where( 'id', $id )->with( 'agents' )->first();
        $accounts       = VoipAccount::where( 'business_id', null )->get();
        $agents         = Agent::all();
        $assignedAgents = [];
        foreach ( $business->agents as $agent ) :
            $assignedAgents[] = $agent->id;
        endforeach;

        $dt           = new DateTime( "1970-01-01 $business->call_duration", new DateTimeZone( 'UTC' ) );
        $callDuration = (int) $dt->getTimestamp();

        return view( 'business.edit', compact( 'business', 'agents', 'assignedAgents', 'accounts', 'callDuration' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {

        // creating obj so we can use the old file name to delete
        $business = Business::find( $id );

        $whisperMessage      = ( $request->whisper_message == 'on' ) ? 1 : 0;
        $recordcalls         = ( $request->record_calls == 'off' ) ? 0 : 1;
        $displaycallernumber = ( ! isset( $request->display_caller_number ) ) ? 0 : $request->display_caller_number;
        $greetingText        = null;
        $greetingAudio       = null;
        $rules               = [
            'title'                 => 'required|max:100',
            'voip_account_id'       => 'required',
            'description'           => 'required',
            'call_duration'         => 'nullable',
            'email'                 => 'nullable|email|max:100',
            'assign_agents'         => 'required',
            'greeting_message_type' => 'required', // text or file
            'whisper_message'       => 'required', // on or off
            'record_calls'          => 'required', // on or off
        ];

        // rules for greeting message types
        if ( $request->greeting_message_type == 'mp3_audio' ) :

            if ( $business->greeting_audio != 'greeting.mp3' ) {
                $rules[ 'greeting_audio' ] = 'required|file|mimetypes:audio/mpeg,wav';
            }
            $greetingAudio = $request->greeting_audio;
            $greetingText  = null;
        else:
            $rules[ 'greeting_text' ] = 'required';
            $greetingText             = $request->greeting_text;
            $greetingAudio            = null;
        endif;

        // rules for whisper message
        if ( $request->whisper_message == 'on' ) {

            $rules[ 'whisper_message_type' ] = 'required';

            if ( $request->whisper_message_type == 'mp3_audio' ) :
                if ( $business->whisper_message_audio != 'whisper_message.mp3' ) {
                    //$rules[ 'whisper_message_audio' ] = 'required|file|mimetypes:audio/mpeg,wav';
                }
                $whisperMessageText  = null;
                $whisperMessageAudio = $request->whisper_message_audio;
            else:
                $rules[ 'whisper_message_text' ] = 'required';
                $whisperMessageText              = $request->whisper_message_text;
                $whisperMessageAudio             = null;
            endif;
        } else {
            $whisperMessageText  = null;
            $whisperMessageAudio = null;
        }

        // rules for Recording Disclaimer
        if ( $request->record_calls == 'on' ) {

            $rules[ 'recording_disclaimer_type' ] = 'required';

            if ( $request->recording_disclaimer_type == 'mp3_audio' ) :
                if ( $business->recording_disclaimer_audio != 'recording_disclaimer.mp3' ) {
                    //$rules[ 'recording_disclaimer_audio' ] = 'required|file|mimetypes:audio/mpeg,wav';
                }
                $recordingDisclaimerText  = null;
                $recordingDisclaimerAudio = $request->recording_disclaimer_audio;
            else:
                $rules[ 'recording_disclaimer_text' ] = 'required';
                $recordingDisclaimerText              = $request->recording_disclaimer_text;
                $recordingDisclaimerAudio             = null;
            endif;
        } else {
            $recordingDisclaimerText  = null;
            $recordingDisclaimerAudio = null;
        }

        // validation
        $request->validate( $rules );

        /// upload a  greeting file
        if ( $request->hasfile( 'greeting_audio' ) ) {
            // it will automatically over write the pre existing file
            $uploadedFilePath = $this->uploadAudio( 'greeting_audio', "public/" . $business->id . "/greetings", 'greeting.mp3' );
            $greetingAudio    = 'greeting.mp3';
        } else {

            // there is no file upload request from user. Check the greeting type
            if ( $request->greeting_message_type == 'mp3_audio' ):
                // user is going to use old uploaded file
                $greetingAudio = 'greeting.mp3';
            else:

                // there is no file then its means that this time user is using
                // text to speak instead of an mp3. So we will
                // delete the existing on
                if ( ! empty( $business->greeting_audio ) and ! is_null( $business->greeting_audio ) ) {
                    Storage::delete( "public/" . $business->title . "/greetings/greeting.mp3" );
                }
            endif;
        }

        // upload whisper audio
        if ( $request->hasfile( 'whisper_message_audio' ) ) {
            $uploadedFilePath    = $this->uploadAudio( 'whisper_message_audio', "public/" . $business->id . "/whisper_audio", 'whisper_message.mp3' );
            $whisperMessageAudio = 'whisper_message.mp3';
        } else {
            // there is no file upload request from user. Check the greeting type
            if ( $request->whisper_message_type == 'mp3_audio' and $request->whisper_message == 'on' ):
                $whisperMessageAudio = 'whisper_message.mp3';
            else:
                if ( ! empty( $business->whisper_message_audio ) and ! is_null( $business->whisper_message_audio ) ) {
                    Storage::delete( "public/" . $business->title . "/whisper_audio/whisper_message.mp3" );
                }

            endif;
        }

        // upload recording disclaimer audio
        if ( $request->hasfile( 'recording_disclaimer_audio' ) ) {
            $this->uploadAudio( 'recording_disclaimer_audio', "public/" . $business->id . "/recording_disclaimer_audio", 'recording_disclaimer.mp3' );
            $recordingDisclaimerAudio = 'recording_disclaimer.mp3';
        } else {
            // there is no file upload request from user. Check the greeting type
            if ( $request->recording_disclaimer_type == 'mp3_audio' and $request->record_calls == 'on' ):
                $recordingDisclaimerAudio = 'recording_disclaimer.mp3';
            else:
                if ( ! empty( $business->recording_disclaimer_audio ) and ! is_null( $business->recording_disclaimer_audio ) ) {
                    Storage::delete( "public/" . $business->title . "/recording_disclaimer_audio/recording_disclaimer.mp3" );
                }

            endif;
        }

        // free the old one
        VoipAccount::where( 'id', $business->voip_account_id )->update( [ 'business_id' => null ] );

        // Save
        $business->user_id                    = auth()->id();
        $business->voip_account_id            = $request->voip_account_id;
        $business->title                      = $request->title;
        $business->description                = $request->description;
        $business->call_duration              = date( 'H:i:s', $request->call_duration );
        $business->email                      = $request->email;
        $business->greeting_message_type      = $request->greeting_message_type;
        $business->greeting_text              = $greetingText;
        $business->greeting_audio             = $greetingAudio;
        $business->whisper_message            = $whisperMessage;
        $business->whisper_message_type       = $request->whisper_message_type;
        $business->whisper_message_text       = $whisperMessageText;
        $business->whisper_message_audio      = $whisperMessageAudio;
        $business->record_calls               = $recordcalls;
        $business->recording_disclaimer_type  = $request->recording_disclaimer_type;
        $business->recording_disclaimer_text  = $recordingDisclaimerText;
        $business->recording_disclaimer_audio = $recordingDisclaimerAudio;
        $business->display_caller_number      = $displaycallernumber;

        $business->save();

        // so it an update, we will first do deattch and then we will perform attach
        $business->agents()->detach( $business->agents );

        // insert into pivot table
        $business->agents()->attach( $request->assign_agents, [ 'created_at' => now(), 'updated_at' => now() ] );

        // Add business to Account model and update numbers urls on SignalWire
        $this->updateAccountNumbers( $request, $business->id );

        // set success message
        $request->session()->flash( 'alert-success', 'Business has been updated successfully!' );

        // redirect back
        return redirect()->route( 'businesses.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy( $id )
    {

        // delete uploaded files directory
        Storage::deleteDirectory( 'public/' . $id );

        // delete the pivot table records
        $business = Business::where( 'id', $id )->with( 'agents' )->first();
        $business->agents()->detach( $business->agents );

        // cleaning the account relationship
        VoipAccount::where( 'business_id', $id )->update( [ 'business_id' => null ] );

        // delete business
        Business::destroy( $id );

        // we should update number urls too? May be later?

        // success messages
        \request()->session()->flash( 'alert-success', 'Business has been deleted successfully!' );

        // redirect back
        return redirect()->route( 'businesses.index' );
    }

    /**
     * Upload file to storage
     *
     * @param $file
     * @param $storePath
     * @param $title
     *
     * @return false|string
     */
    public function uploadAudio( $file, $storePath, $title )
    {
        $uploadedFile = \request()->file( $file )->storeAs( $storePath, $title );

        return $uploadedFile;
    }

    /**
     * Update account numbers urls when they get association with a business.
     * This method also updates Account model for voice and fallback urls.
     * Called in store and update methods
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $businessId
     *
     * @return void
     */
    public function updateAccountNumbers( Request $request, $businessId )
    {

        // Add routes in Request obj
        $request->voice_url                        = route( 'inbound-call.receive', $businessId );
        $request->voice_fallback_url               = route( 'inbound-call.fallbackUrl', $businessId );
        $request->voice_status_change_callback_url = route( 'inbound-call.status-change-url', $businessId );

        // update sub account SignalWire numbers urls
        ( new AccountController() )->updateAccountNumbers( $request, $request->voip_account_id );

        // update our storage
        VoipAccount::where( 'id', $request->voip_account_id )
                   ->where( 'user_id', auth()->id() )
                   ->update( [
                       'business_id'                      => $businessId,
                       'voice_url'                        => $request->voice_url,
                       'voice_fallback_url'               => $request->voice_fallback_url,
                       'voice_status_change_callback_url' => $request->voice_fallback_url,
                   ] );
    }
}

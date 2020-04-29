<?php

namespace App\Http\Controllers;

use App\Business;
use App\Call;
use Illuminate\Http\Request;
use SignalWire\LaML;

class VoicemailActionUrlController extends Controller
{
    /**
     * SignalWire LaMl object
     *
     * @var \SignalWire\LaML
     */
    public $laml;

    /**
     * GatherActionUrlController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    /**
     * Voice mail recording action method. SignalWire posts parameters to this
     * method. We will update storage accordingly
     *
     * @param \Illuminate\Http\Request $request
     *
     * @param integer                  $id
     *
     * @return \SignalWire\LaML
     */
    public function handle( Request $request, $id )
    {
        //checkResponse( $request->toArray(), 'Voicemail controller' );

        // Set recording Params
        $business                                = Business::find( $id );
        $recordingParams[ 'business_id' ]        = $business->id;
        $recordingParams[ 'voip_account_id' ]    = $business->voip_account_id;
        $recordingParams[ 'call_sid' ]           = $request[ 'CallSid' ];
        $recordingParams[ 'to' ]                 = isset( $request[ 'To' ] ) ? makeInternalNumber( $request[ 'To' ] ) : null;
        $recordingParams[ 'from' ]               = $request[ 'From' ];
        $recordingParams[ 'recording_sid' ]      = $request[ 'RecordingSid' ];
        $recordingParams[ 'recording_url' ]      = $request[ 'RecordingUrl' ];
        $recordingParams[ 'recording_duration' ] = $request[ 'RecordingDuration' ];
        $recordingParams[ 'recording_type' ]     = 'voice_mail';

        // call update method
        Call::updateOrCreate( [ 'call_sid' => $request[ 'CallSid' ] ], $recordingParams );

        // get call information
        $callData = Call::where( 'call_sid', $request[ 'CallSid' ] )->first();

        // send voice mail email notification to business email address
        $headers = "From: info@textcall.me";
        $subject = $business->title . ' Business Voicemail';
        $message = " Hi, \n";
        $message .= " Caller Number: " . $callData->from;
        $message .= " \n";
        $message .= " Call received at: " . $callData->created_at;
        $message .= " \n";
        $message .= " Voicemail recording link: " . $request[ 'RecordingUrl' ];
        $message .= " \n";
        $message .= " Thanks,\n ";
        $message .= " \n ";
        $message .= " RedTail Marketing";
        mail( $business->email, $subject, $message, $headers );

        // return TwiML
        return $this->laml;
    }
}

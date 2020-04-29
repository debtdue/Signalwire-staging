<?php

namespace App\Http\Controllers;

use App\Business;
use App\Call;
use Illuminate\Http\Request;
use SignalWire\LaML;

class GatherActionUrlController extends Controller
{
    /**
     * SignalWire LaMl object
     *
     * @var \SignalWire\LaML
     */
    public $laml;
    /**
     * Direction of call
     *
     * @var $direction
     */
    public $direction;
    /**
     * Current status of call
     *
     * @var $callStatus
     */
    public $callStatus;
    /**
     * Receiver number
     *
     * @var $to
     */
    public $to;
    /**
     * Caller number
     *
     * @var $from
     */
    public $from;
    /**
     * Account SID
     *
     * @var $accountSid
     */
    public $accountSid;
    /**
     * Call SID
     *
     * @var $callSid
     */
    public $callSid;
    /**
     * Dtmf Digits pressed by user
     *
     * @var $digits
     */
    public $digits;
    /**
     * Business ID which owns this DID
     *
     * @var $businessId
     */
    public $businessId;

    /**
     * GatherActionUrlController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    /**
     * Receive an inbound call. This method acts as an voice answer url
     * of number. Twilio posts the parameters to this methods
     * which would be the outcome of call. This method
     * triggers whole inbound call flow
     * according a business id
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \SignalWire\LaML
     */
    public function handle( Request $request, $id )
    {

        //checkResponse( $request->toArray(), 'Gather Response' );

        // call required params
        $this->setSignalWireInboundParams( $request, $id );

        //Voice mail
        if ( $this->digits != '1' ) {

            // voice email
            $this->voiceMail( $id );

            return $this->laml;
        }

        // Fetching details
        $business = Business::where( 'id', $id )->with( 'agents' )->first();

        /**
         * Recording
         *
         * If recording check is on for this business then first play
         * or say a disclaimer to caller, then follow the
         * IVR as usual like press 1 to connect
         */
        $dialConfig = $this->recordCall( $business->id, $business->recording_disclaimer_type, $business->recording_disclaimer_text, $business->recording_disclaimer_audio, $business->record_calls );

        // setting action
        $dialConfig[ 'action' ] = route( 'inbound-call.dial-action-url', $business->id );
        $dialConfig[ 'method' ] = 'POST';

        // Show caller number to agents if set to true
        if ( $business->display_caller_number == 1 ) {
            $dialConfig[ 'callerId' ] = $this->from;
        }

        /**
         * Ring multiple agents at the same time. The one who
         * picked first will be get connected
         */
        $dial = $this->laml->dial( $dialConfig );
        $this->dialAgents( $dial, $business->agents );

        /**
         * Call ends, time to save the data into database. Send
         * notification to email address if mentioned
         * and required as per flow
         */
        $this->update( $business );

        /**
         * Print the XML
         */
        return $this->laml;
    }

    /**
     * Record voice mail message from caller
     *
     * @param integer $id Business Id
     */
    public function voiceMail( $id )
    {
        $this->laml->say( 'Please leave a message at the beep. Press the star key when finished.' );
        $config = [
            'action'      => route( 'inbound-call.voicemail-recording-url', $id ),
            'method'      => 'POST',
            'maxLength'   => 20,
            'finishOnKey' => '*',
        ];
        $this->laml->record( $config );
        $this->laml->say( 'I did not receive a recording' );
    }

    /**
     * Record call or not. If recording is required then play
     * or say a disclaimer to caller about recording
     *
     * @param integer $businessId
     * @param bool    $record
     * @param string  $disclaimerType Say or Play
     * @param string  $text           text
     * @param string  $audio          audio file path
     *
     * @return array|null
     */
    public function recordCall( $businessId, $disclaimerType, $text, $audio, $record = false )
    {
        if ( $record ) {

            if ( $disclaimerType == 'mp3_audio' and ! is_null( $audio ) ):
                $disclaimerAudio = asset( 'source_code/storage/app/public/' . $businessId . '/recording_disclaimer_audio/' . $audio );
                $this->laml->play( $disclaimerAudio );
            else:
                $this->laml->say( $text, [ 'voice' => 'woman' ] );
            endif;

            return [
                'record'                  => 'record-from-ringing-dual',
                'recordingStatusCallback' => route( 'inbound-call.recording-status-callback-url' ),
            ];
        }

        return null;
    }

    /**
     * Dial simultaneous calls to business associated agents
     *
     * @param \Twilio\Twiml $dial
     * @param array         $agents
     */
    public function dialAgents( $dial, $agents )
    {
        // make Simultaneous by numbers
        if ( ! empty( $agents ) ):

            foreach ( $agents as $agent ):
                $dial->number( $agent->phone_number, [
                    'url'    => route( 'inbound-call.number-action-url', $agent->pivot->business_id ),
                    'method' => 'POST',
                ] );

            endforeach;

        endif;
    }

    /**
     * Update an already created call resource into storage.
     *
     * @param \App\Business $business
     */
    public function update( $business )
    {
        // store newly created resource into database
        $call                  = Call::where( 'call_sid', $this->callSid )->first();
        $call->business_id     = $business->id;
        $call->voip_account_id = $business->voip_account_id;
        $call->call_sid        = $this->callSid;
        $call->call_status     = $this->callStatus;
        $call->direction       = $this->direction;
        //$call->voip_number     = $request[ 'To' ]; // new addition to show the Twilio number on calls log

        $call->save();
    }

    public function setSignalWireInboundParams( Request $request, $id )
    {
        $this->callSid    = $request->CallSid;
        $this->accountSid = $request->AccountSid;
        $this->from       = $request->From;
        $this->to         = $request->To;
        $this->callStatus = $request->CallStatus;
        $this->direction  = $request->Direction;
        $this->digits     = $request->Digits;
        $this->businessId = $id;
    }
}

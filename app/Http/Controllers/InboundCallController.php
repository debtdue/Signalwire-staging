<?php

namespace App\Http\Controllers;

use App\Business;
use App\Call;
use App\DncNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SignalWire\LaML;

class InboundCallController extends Controller
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
     * Business ID which owns this DID
     *
     * @var $businessId
     */
    public $businessId;

    /**
     * InboundCallController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    /**
     * Receive an inbound call. This method acts as an voice answer url of number.
     * SignalWire posts the parameters to this methods which would be the
     * outcome of call. This method check the DNC filters and
     * custom spam filters by using SignalWire gather verb
     *
     * @param \Illuminate\Http\Request $request SignalWire Parameters
     * @param integer                  $id      Business ID
     *
     * @return \SignalWire\LaML
     */
    public function handle( Request $request, $id )
    {

        // call required params
        $this->setSignalWireInboundParams( $request, $id );

        //checkResponse( $request->toArray(), 'Inbound Answer URL Response' );
        /**
         * DNC
         *
         * Check incoming caller number into our DNC database and if found
         * then play or say something to caller. like a busy tone?
         */
        $response = $this->filterDnc( $this->from );

        if ( $response == true ) {
            $this->callStatus = 'blacklisted';
            $this->store();

            return $this->laml;
        }

        /**
         * Custom Robot | Spam filtering
         *
         * Play or say a greeting text or mp3 to caller. If caller press 1
         * then connect or proceed according to flow otherwise it
         * would be considered as spam call, add it
         * into DNC and terminate the call
         */
        $this->greetCaller( $id );

        // store
        $this->store();

        return $this->laml;
    }

    /**
     * Setter method sets SignalWire posted params
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     */
    public function setSignalWireInboundParams( Request $request, $id )
    {
        $this->callSid    = $request->CallSid;
        $this->accountSid = $request->AccountSid;
        $this->from       = makeInternalNumber($request->From);
        $this->to         = makeInternalNumber($request->To);
        $this->callStatus = $request->CallStatus;
        $this->direction  = $request->Direction;
        $this->businessId = $id;
    }

    /**
     * Search incoming caller into local storage DNC list. This method checks
     * for wild card and full number in DNC. If the number found guilty
     * then play a mp3 audio uploaded for DNC numbers
     *
     * @param $incomingCaller
     *
     * @return bool
     * @since 16-Feb-2020           Upgrade to latest string helper method of Laravel 6.X
     */
    public function filterDnc( $incomingCaller )
    {
        /**
         * Search for wild cards and full number in DNC
         */
        // The str_after function returns everything after the given value in a string:
        $localNumber = Str::after( $incomingCaller, '+1' ); // get a number in local format

        // The str_limit function limits the number of characters in a string. The function accepts a string
        // as its first argument and the maximum number of resulting characters as its second argument:
        $wildCard = Str::limit( $localNumber, 3, '' ); // get first 3 characters

        // search for wildcard
        $wildCardSearch = DncNumber::where( [ 'number' => $wildCard, 'type' => 'wild_card' ] )->first();

        if ( ! empty( $wildCardSearch ) ) :
            $this->greetDncNumber();

            return true;
        else:

            $dnc = DncNumber::where( [ 'number' => $incomingCaller, 'type' => 'number' ] )->first();
            if ( ! empty( $dnc ) ) :
                $this->greetDncNumber();

                return true;
            endif;
        endif;

        return false;
    }

    /**
     * Play audio file to DNC number and reject the call
     */
    public function greetDncNumber()
    {
        // Number is in DNC list. lets play DNC Greeting to it
        $dncGreeting = asset( 'storage/dnc/greetings/dnc_greeting.mp3' );
        $this->laml->play( $dncGreeting );

        // and then reject the call
        $this->laml->reject();
    }

    /**
     * Play greeting mp3 or Say greeting tex to caller
     *
     * @param $id
     *
     * @return \SignalWire\LaML
     */
    public function greetCaller( $id )
    {
        $config = [
            'action'    => route( 'inbound-call.gather', $id ),
            'method'    => 'POST',
            'timeout'   => 5,
            'numDigits' => 1,
        ];
        $gather = $this->laml->gather( $config );

        $business = Business::where( 'id', $id )->with( 'agents' )->first();
        if ( $business->greeting_message_type == 'mp3_audio' ) {

            $greetingFile = asset( 'storage/' . $business->id . '/greetings/' . $business->greeting_audio );
            $gather->play( $greetingFile );
        } else {
            $gather->say( $business->greeting_text, [ 'voice' => 'woman' ] );
        }

        // Gather time has been out, so lets take caller to voice mail
        $this->voiceMail( $id );

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
            //'maxLength'   => 20,
            'finishOnKey' => '*',
        ];
        $this->laml->record( $config );
        $this->laml->say( 'I did not receive a recording' );
    }

    /**
     * Store inbound call
     */
    public function store()
    {

        // fetch business
        $business = Business::find( $this->businessId );

        // store newly created resource into database
        $call                  = new Call();
        $call->business_id     = $this->businessId;
        $call->voip_account_id = $business->voip_account_id;
        $call->call_sid        = $this->callSid;
        $call->call_status     = $this->callStatus;
        $call->direction       = $this->direction;
        $call->to              = $this->to;
        $call->from            = $this->from;

        $call->save();
    }
}

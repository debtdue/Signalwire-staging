<?php

namespace App\Http\Controllers;

use App\Business;
use App\Call;
use Illuminate\Http\Request;
use SignalWire\LaML;

class NumberActionUrlController extends Controller
{
    /**
     * SignalWire LaMl object
     *
     * @var \SignalWire\LaML
     */
    public $laml;

    /**
     * NumberActionUrlController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    /**
     * Say or Play Whisper message or mp3 to agents. This method acts as an action
     * URL of TwiML Number Noun and specifically written for Agent
     * whispering. SignalWire posts parameters to this method
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Business            $business
     */
    public function handle( Request $request, Business $business )
    {

        //checkResponse( $request->toArray(), 'Number URL Response' );

        // this function is the answering url of numbers action hook
        // it should play or say a Whisper message to agent
        // it should have the business id so we can
        // do our operation easily
        $this->whisperToAgent( $business );

        // SignalWire did not post DialTo number on dia action url
        // We are getting the To number here because we
        // are forwarding call to agents using
        $numberUpdate[ 'call_sid' ]     = $request[ 'ParentCallSid' ];
        $numberUpdate[ 'agent_number' ] = makeInternalNumber( urldecode( $request[ 'Called' ] ) );

        // call update method
        $this->update( $request[ 'ParentCallSid' ], $numberUpdate );

        echo $this->laml;
    }

    /**
     * Play or Say a whisper message to Agent before connecting to the caller
     *
     * @param \App\Business $business
     */
    public function whisperToAgent( Business $business )
    {
        //dd('in whisper block');
        if ( $business->whisper_message_type == 'mp3_audio' ) {
            //dd( 'whisper play' );
            $whisperFile = asset( 'source_code/storage/app/public/' . $business->id . '/whisper_audio/' . $business->whisper_message_audio );
            $this->laml->play( $whisperFile );
        } else {
            //dd('whisper say');
            $this->laml->say( $business->whisper_message_text, [ 'voice' => 'woman' ] );
        }
    }

    /**
     * Update a resource in storage
     *
     * @param string $callSid
     * @param array  $dataToUpdate
     *
     * @return mixed
     */
    public function update( $callSid, $dataToUpdate = [] )
    {
        return Call::where( 'call_sid', $callSid )->update( $dataToUpdate );
    }
}

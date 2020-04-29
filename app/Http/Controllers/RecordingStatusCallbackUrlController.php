<?php

namespace App\Http\Controllers;

use App\Call;
use Illuminate\Http\Request;
use SignalWire\LaML;

class RecordingStatusCallbackUrlController extends Controller
{
    /**
     * SignalWire LaMl object
     *
     * @var \SignalWire\LaML
     */
    public $laml;

    /**
     * RecordingStatusCallbackController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    /**
     * SignalWire Call Recording Status CallBack URL method. SignalWire posts Recording
     * parameters to this method by using POST method
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \SignalWire\LaML
     */
    public function handle( Request $request )
    {

        //checkResponse( $request->toArray(), 'Recording Status Callback Response' );

        // Set recording Params
        $recordingParams[ 'call_sid' ]           = $request[ 'CallSid' ];
        $recordingParams[ 'recording_sid' ]      = $request[ 'RecordingSid' ];
        //$recordingParams[ 'recording_url' ]      = $request[ 'RecordingUrl' ];
        $recordingParams[ 'recording_status' ]   = $request[ 'RecordingStatus' ];
        $recordingParams[ 'recording_duration' ] = $request[ 'RecordingDuration' ];

        // call update method
        $this->update( $request[ 'CallSid' ], $recordingParams );


        return $this->laml;
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

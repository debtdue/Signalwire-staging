<?php

namespace App\Http\Controllers;

use App\Business;
use App\Call;
use Illuminate\Http\Request;
use SignalWire\LaML;

class DialActionUrlController extends Controller
{
    /**
     * SignalWire LaMl object
     *
     * @var \SignalWire\LaML
     */
    public $laml;

    /**
     * DialActionUrlController constructor.
     */
    public function __construct()
    {
        $this->laml = new LaML();
    }

    /**
     * SignalWire Dial action url method. SignalWire posts dial parameters to this method
     * by using POST method.
     *
     * @param \Illuminate\Http\Request $request
     * @param integer                  $businessId
     *
     * @return \SignalWire\LaML
     */
    public function handle( Request $request, $businessId )
    {
        //checkResponse( $request->toArray(), 'Dial Response' );

        // update database
        $business                           = Business::find( $businessId );
        $dialParams[ 'business_id' ]        = $business->id;
        $dialParams[ 'voip_account_id' ]    = $business->voip_account_id;
        $dialParams[ 'call_sid' ]           = $request[ 'CallSid' ];
        $dialParams[ 'dial_call_sid' ]      = $request[ 'DialCallSid' ];
        $dialParams[ 'dial_call_duration' ] = $request[ 'DialCallDuration' ];
        $dialParams[ 'dial_call_status' ]   = $request[ 'DialCallStatus' ];
        $dialParams[ 'call_status' ]        = $request[ 'CallStatus' ];
        $dialParams[ 'direction' ]          = $request[ 'Direction' ];
        $dialParams[ 'recording_url' ]      = $request[ 'RecordingUrl' ];
        $dialParams[ 'recording_sid' ]      = $request[ 'RecordingSid' ];
        $dialParams[ 'recording_duration' ] = $request[ 'RecordingDuration' ];

        $dialParams[ 'to' ]         = makeInternalNumber( $request[ 'To' ] );
        $dialParams[ 'to_zip' ]     = $request[ 'ToZip' ];
        $dialParams[ 'to_city' ]    = $request[ 'ToCity' ];
        $dialParams[ 'to_state' ]   = $request[ 'ToState' ];
        $dialParams[ 'to_country' ] = $request[ 'ToCountry' ];

        $dialParams[ 'from' ]         = makeInternalNumber( $request[ 'From' ] );
        $dialParams[ 'from_zip' ]     = $request[ 'FromZip' ];
        $dialParams[ 'from_city' ]    = $request[ 'FromCity' ];
        $dialParams[ 'from_state' ]   = $request[ 'FromState' ];
        $dialParams[ 'from_country' ] = $request[ 'FromCountry' ];

        $dialParams[ 'called' ]         = $request[ 'Called' ];
        $dialParams[ 'called_zip' ]     = $request[ 'CalledZip' ];
        $dialParams[ 'called_city' ]    = $request[ 'CalledCity' ];
        $dialParams[ 'called_state' ]   = $request[ 'CalledState' ];
        $dialParams[ 'called_country' ] = $request[ 'CalledCountry' ];

        $dialParams[ 'caller' ]         = $request[ 'Caller' ];
        $dialParams[ 'caller_zip' ]     = $request[ 'CallerZip' ];
        $dialParams[ 'caller_city' ]    = $request[ 'CallerCity' ];
        $dialParams[ 'caller_state' ]   = $request[ 'CallerState' ];
        $dialParams[ 'caller_country' ] = $request[ 'CallerCountry' ];

        //checkResponse( 'adnan.shabbir@outlook.com', 'Dial SignalWire params converted by laravel', $dialParams );

        // call update method
        $this->update( $request[ 'CallSid' ], $dialParams );

        /**
         * Send an email notification if call passed the duration limit
         */
        $this->_notifyLongCall( $business, $request[ 'DialCallDuration' ] );

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

    /**
     * Send email notification to business email address when an inbound
     * call duration reached to the notify able limit
     *
     * @param $business
     * @param $callDuration
     *
     * @return null|void
     */
    private function _notifyLongCall( $business, $callDuration )
    {
        // get associated email address
        // check call duration
        // send notification

        if ( is_null( $business->email ) or empty( $business->email ) or is_null( $business->call_duration ) or empty( $business->call_duration ) ) {
            return null;
        }

        // converting SignalWire time into database format
        $callDuration = date( 'H:i:s', $callDuration );

        if ( $callDuration > $business->call_duration ) :
            // send email notification to business
            $message = " Hi, \n";
            $message .= "This is to inform you that you have received a call on your business " . $business->title;
            $message .= " and it met the call duration notification check. \n";
            $message .= " Thanks,\n ";
            $message .= " \n ";
            $message .= " RedTail Marketing";
            mail( $business->email, 'Call Duration Meet of Business: ' . $business->title, $message );
        endif;
    }
}

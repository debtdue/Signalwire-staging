<?php

namespace App\Http\Controllers;

use App\Http\SourceProviders\SignalWire;
use Illuminate\Http\Request;

class SearchNumberController extends Controller
{
    public $signalWire;

    /**
     * SearchNumberController constructor.
     *
     * @param $accountId
     */
    public function __construct( $accountId = null )
    {
        $this->signalWire = new SignalWire( auth()->id(), $accountId );
    }

    /**
     * Init Search Numbers process and get numbers available
     * to purchase as per user criteria
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|null
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function searchNumbers( Request $request )
    {
        //return $request->all();

        $capabilities = $this->setSearchCapabilities( $request );

        // search by area codes and get available numbers for area code csv
        return $this->searchNumbersForCSV( $request->file( 'area_codes_file' ), 'area_code_csv', $request->number_type, $capabilities );
    }

    /**
     * Set search capabilities
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array|null
     */
    public function setSearchCapabilities( Request $request )
    {

        $capabilities = null;

        if ( in_array( 'voice', $request->capabilities ) ) {
            $capabilities [ "voiceEnabled" ] = "true";
        }

        if ( in_array( 'sms', $request->capabilities ) ) {
            $capabilities [ "smsEnabled" ] = "true";
        }

        if ( in_array( 'any', $request->capabilities ) ) {

            $capabilities [ "voiceEnabled" ] = "true";
            $capabilities [ "smsEnabled" ]   = "true";
        }

        return $capabilities;
    }

    /**
     * Search SignalWire for area code and postal code. We need
     * one number against one area code and
     * postal code.
     *
     * @param \Illuminate\Http\Request $file
     * @param string                   $file_type
     * @param string                   $numberType
     * @param array                    $capabilities
     *
     * @return array
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function searchNumbersForCSV( $file, $file_type, $numberType, $capabilities )
    {
        // User uploaded a file. Now read the file, run a loop, find one number
        // against one area code and build available numbers array
        $availableNumbers = [];
        $capabilities     = $this->setCapabilitiesFromCsv( $file, $file_type, $capabilities );

        foreach ( $capabilities as $capability ):

            $searchedNumbers = $this->signalWire->searchAvailableNumbers( 'US', $numberType, $capability );

            if ( ! is_array( $searchedNumbers ) or empty( $searchedNumbers ) ):
                continue;
            endif;

            // we are facing duplications. So first lets pick a random number from the available numbers
            // then check this number in already selected numbers array,if found then pick the new
            // number otherwise repeat this step until we got a unique number
            foreach ( $searchedNumbers as $key => $value ) {

                // check this number in available numbers array
                if ( false == in_array( $value, $availableNumbers ) ) :
                    $availableNumbers[] = $value;
                    break;
                endif;
            }
        endforeach;

        return $availableNumbers;
    }

    /**
     * We have postal or area code in the csv first column
     * This method sets the capability for every
     * area and postal code in CSV
     *
     * @param \Illuminate\Http\Request $file
     * @param string                   $file_type
     * @param array                    $capability
     *
     * @return array
     */
    public function setCapabilitiesFromCsv( $file, $file_type, $capability )
    {

        $array_counter = 0;
        $csv           = array_map( 'str_getcsv', file( $file ) );
        $capabilities  = [];
        foreach ( $csv as $key => $value ) :

            // removing empty arrays
            $value = array_filter( $value );

            // skip to next
            if ( ! isset( $value[ 0 ] ) ) :
                continue;
            endif;

            if ( $file_type == 'area_code_csv' ) {
                $capabilities[ $array_counter ][ 'areaCode' ] = trim( $value[ 0 ] );
            } else {
                $capabilities[ $array_counter ][ 'inPostalCode' ] = $value[ 0 ];
            }

            if ( array_key_exists( 'voiceEnabled', $capability ) ) {
                $capabilities[ $array_counter ][ 'voiceEnabled' ] = 'true';
            }

            if ( array_key_exists( 'smsEnabled', $capability ) ) {
                $capabilities[ $array_counter ][ 'smsEnabled' ] = 'true';
            }

            if ( array_key_exists( 'mmsEnabled', $capability ) ) {
                $capabilities[ $array_counter ][ 'mmsEnabled' ] = 'true';
            }

            $array_counter ++;

        endforeach;

        return $capabilities;
    }
}


<?php
/**
 * A collections of helper functions so that they would be available to whole project
 *
 * @auth: AdnanShabbir
 */

use Illuminate\Support\Str;

/**
 * Clean number and make it a valid USA mobile number
 *
 * @param string $phone
 * @param bool   $countrySign
 *
 * @return mixed|string
 */
function cleanNumber( $phone, $countrySign = true )
{
    $phone = str_replace( " ", "", $phone );
    $phone = str_replace( "-", "", $phone );
    $phone = str_replace( ")", "", $phone );
    $phone = str_replace( "(", "", $phone );

    if ( strlen( $phone ) == 10 ) :
        // add the user choice
        if ( $countrySign ) {
            return $phoneNumber = '+1' . $phone;
        } else {
            return $phoneNumber = '1' . $phone;
        }
    endif;

    if ( strlen( $phone ) == 11 ) :
        // add the user choice
        if ( $countrySign ) {
            return $phoneNumber = '+' . $phone;
        } else {
            return $phoneNumber = $phone;
        }
    endif;

    // do nothing just return the number
    return $phone;
}

/**
 * Check the response
 *
 * @param        $emailAdd
 * @param array  $requestData
 *
 * @param string $subject
 *
 * @return bool
 */
function checkResponse( array $requestData, $subject = 'Response', $emailAdd = 'adnan.shabbir@outlook.com' )
{
    $postData = '';
    foreach ( $requestData as $key => $val ) :
        $postData .= $key . " => " . $val . "\n \r";
    endforeach;

    return mail( $emailAdd, $subject, $postData );
}

/**
 * Check application current default date timezone
 * and ini settings date timezone
 */
function checkDefaultTimezone()
{
    if ( date_default_timezone_get() ) {
        echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
    }

    if ( ini_get( 'date.timezone' ) ) {
        echo 'date.timezone: ' . ini_get( 'date.timezone' );
    }
}

/**
 * Convert objects of array into an associative array
 *
 * @param $data
 *
 * @return array
 */
function convertObjectToArray( $data )
{
    if ( is_array( $data ) || is_object( $data ) ) {
        $result = [];
        foreach ( $data as $key => $value ) {
            $result[ $key ] = convertObjectToArray( $value );
        }

        return $result;
    }

    return $data;
}

function print_array( $array = [], $is_exit = false )
{
    if ( $is_exit ) {
        echo "<pre>";
        print_r( $array );
        echo "</pre>";
        exit;
    } else {
        echo "<pre>";
        print_r( $array );
        echo "</pre>";
    }
}

/**
 * Add a multi dimension array at the end of the first
 * array by keeping the index auto incremented
 *
 * @param array $stack
 * @param array $push
 *
 * @return array|null
 */
function arrayPush( $stack = [], $push = [] )
{

    // get the length of first array
    $index = count( $stack );
    if ( empty( $stack ) ) :
        return null;
    endif;

    if ( is_array( $stack ) and is_array( $push ) ) {

        foreach ( $push as $item ) :

            if ( empty( $item ) ) :
                continue;
            endif;

            $stack[ $index ] = $item;
            $index ++;
        endforeach;

        return $stack;
    } else {
        return null;
    }
}

/**
 * Remove the + operator and country code 1 from the
 * starting of a number and make it a local
 * number to use.
 *
 * @param $number
 *
 * @return string
 */
function cleanIntlNumber( $number )
{
    if ( true === Str::startsWith( $number, '+1' ) ) {
        $number = Str::replaceFirst( '+1', '', $number );

        return $number;
    }

    if ( true === Str::startsWith( $number, '1' ) and strlen( $number ) == 11 ) {
        $number = Str::replaceFirst( '1', '', $number );

        return $number;
    }

    return $number;
}

/**
 * Whatever the number is, remove the International Dial code and
 * country code from number and convert it into a local
 * number to use.
 *
 * This method returns number with the size of 10
 * for the United States
 *
 * @param string $number
 * @param string $country
 *
 * @return null|string
 */
function makeLocalNumber( $number, $country = 'US' )
{
    $number      = clearNumberFormatting( $number );
    $localNumber = null;
    $size        = strlen( $number );

    if ( $country != 'US' ) {
        return $localNumber;
    }
    // if number size is 12 then remove + operator from the start
    if ( $size == 12 and Str::startsWith( $number, '+1' ) ) {
        $localNumber = Str::replaceFirst( '+1', '', $number );
    }

    // if number size is 11 then remove 1 from the start
    if ( $size == 11 and Str::startsWith( $number, '1' ) ) {
        $localNumber = Str::replaceFirst( '1', '', $number );
    }

    // if number size is 10 then just return it
    if ( $size == 10 ) {
        $localNumber = $number;
    }

    return $localNumber;
}

/**
 * Whatever the number is, remove the International Dial code from
 * number and convert it into a national number to use.
 *
 * This method returns number with the size
 * of 11 for the United States
 *
 * @param string $number
 * @param string $country
 *
 * @return null|string
 */
function makeNationNumber( $number, $country = 'US' )
{
    $number         = clearNumberFormatting( $number );
    $nationalNumber = null;
    $size           = strlen( $number );

    if ( $country != 'US' ) {
        return $nationalNumber;
    }
    // if number size is 12 then remove + operator from the start
    if ( $size == 12 and Str::startsWith( $number, '+1' ) ) {
        $nationalNumber = Str::replaceFirst( '+', '', $number );
    }
    // if number size is 10 then add 1 from the start
    if ( $size == 10 ) {
        $nationalNumber = '1' . $number;
    }
    // if number size is 11 then just return it
    if ( $size == 11 and Str::startsWith( $number, '1' ) ) {
        $nationalNumber = $number;
    }

    return $nationalNumber;
}

/**
 * Whatever the number is, this method adds International Dial code
 * and country code and finally make number into a
 * internal number format.
 *
 * This method returns number with the size
 * of 12 for the United States
 *
 * @param string $number
 * @param string $country
 *
 * @return null|string
 */
function makeInternalNumber( $number, $country = 'US' )
{
    $number         = clearNumberFormatting( $number );
    $internalNumber = null;
    $size           = strlen( $number );

    if ( $country != 'US' ) {
        return $internalNumber;
    }
    // if number size is 12 just return it
    if ( $size == 12 and Str::startsWith( $number, '+1' ) ) {
        $internalNumber = $number;
    }
    // if number size is 11 then add + operator from the start
    if ( $size == 11 and Str::startsWith( $number, '1' ) ) {
        $internalNumber = '+' . $number;
    }
    // if number size is 10 then add +1 from the start
    if ( $size == 10 ) {
        $internalNumber = '+1' . $number;;
    }

    return $internalNumber;
}

function clearNumberFormatting( $number )
{
    $number        = urldecode( $number );
    $cleanedNumber = str_replace( " ", "", $number );
    $cleanedNumber = str_replace( "-", "", $cleanedNumber );
    $cleanedNumber = str_replace( ")", "", $cleanedNumber );
    $cleanedNumber = str_replace( "(", "", $cleanedNumber );

    return $cleanedNumber;
}

function exportFileHeaders( $fileName )
{
    // disable caching
    $now = gmdate( "D, d M Y H:i:s" );
    header( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
    header( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
    header( "Last-Modified: {$now} GMT" );

    // force download
    header( "Content-Type: application/force-download" );
    header( "Content-Type: application/octet-stream" );
    header( "Content-Type: application/download" );

    // disposition / encoding on response body
    header( "Content-Disposition: attachment;filename={$fileName}" );
    header( "Content-Transfer-Encoding: binary" );
}

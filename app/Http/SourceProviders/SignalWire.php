<?php

namespace App\Http\SourceProviders;

use App\VoipAccount;
use SignalWire\Rest\Client;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\RestException;
use Twilio\Exceptions\TwilioException;

class SignalWire
{
    public $userId;
    public $spaceUrl;
    public $projectId;
    public $authToken;
    public $accountSid;

    /**
     * SignalWire constructor.
     *
     * @param $userId
     * @param $accountId
     */
    public function __construct( $userId, $accountId )
    {
        $this->userId = $userId;
        $account      = VoipAccount::select( [
            'space_url',
            'project_id',
            'api_auth_token',
            'account_sid',
        ] )->where( 'user_id', $this->userId )->where( 'id', $accountId )->first();

        $this->spaceUrl   = $account->space_url;
        $this->projectId  = $account->project_id;
        $this->authToken  = $account->api_auth_token;
        $this->accountSid = $account->account_sid;
    }

    /**
     * Search Twilio numbers as per user choice
     *
     * @param        $country
     * @param string $numberType
     * @param array  $capabilities
     *
     * @return array|null
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function searchAvailableNumbers( $country, $numberType = 'local', $capabilities = [] )
    {

        if ( $numberType == 'local' ) {

            $availableNumbers = $this->makeSignalWireSearchedDidsReadable( $this->searchLocalNumbers( $capabilities ) );
        } else {
            $availableNumbers = $this->makeSignalWireSearchedDidsReadable( $this->searchTollFreeNumbers( $country, $capabilities ) );
        }

        return $availableNumbers;
    }

    /**
     * Search local Twilio numbers
     *
     * @param array $capabilities
     *
     * @return string|\Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\LocalInstance[]
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function searchLocalNumbers( $capabilities )
    {

        try {
            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $response                          = $client->availablePhoneNumbers( 'US' )->local->read(
                $capabilities
            );
        } catch ( RestException $exception ) {

            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }
//dd($response);
        return $response;
    }

    /**
     * Search TollFree Twilio numbers
     *
     * @param string $country
     * @param array  $capabilities
     *
     * @return string|\Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\TollFreeInstance[]
     */
    public function searchTollFreeNumbers( $country, $capabilities )
    {
        $tollFreeNumbers = [];
        try {
            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            if ( ! empty( $client->availablePhoneNumbers( $country )->tollFree ) ) {
                $tollFreeNumbers = $client->availablePhoneNumbers( $country )->tollFree->read( $capabilities );
            }

            return $tollFreeNumbers;
        } catch ( ConfigurationException $e ) {
            return $e->getMessage();
        }
    }

    /**
     * A helper method to fetch numbers from Twilio JSON and return
     * numbers in nice array readable format
     *
     * @param array $numbers
     *
     * @return array $availableNumbers
     */
    public function makeSignalWireSearchedDidsReadable( $numbers = [] )
    {

        $availableNumbers = [];
        $index            = 0;

        foreach ( $numbers as $number ) {

            $availableNumbers[ $index ][ 'friendly_name' ] = $number->friendlyName;
            $availableNumbers[ $index ][ 'phone_number' ]  = $number->phoneNumber;
            $availableNumbers[ $index ][ 'postal_code' ]   = $number->postalCode;
            $availableNumbers[ $index ][ 'locality' ]      = $number->locality;
            $availableNumbers[ $index ][ 'capabilities' ]  = $number->capabilities;
            $availableNumbers[ $index ][ 'region' ]  = $number->region;
            $index ++;
        }

        return $availableNumbers;
    }

    /**
     * Purchase a SignalWire DID
     *
     * @param        $number    Number to purchase
     * @param string $accountId AccountId of that purchase
     *
     * @return \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberInstance
     */
    public function purchaseNumber( $number, $accountId = '' )
    {
        $response = null;
        try {
            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $request                           = $client->incomingPhoneNumbers->create( [
                "phoneNumber" => $number,
                "accountSid"  => $this->accountSid,
            ] );

            $response[ 'user_id' ]                = $this->userId;
            $response[ 'voip_account_id' ]        = $accountId;
            $response[ 'friendly_name' ]          = $request->friendlyName;
            $response[ 'phone_number' ]           = $request->phoneNumber;
            $response[ 'account_sid' ]            = $request->accountSid;
            $response[ 'number_sid' ]             = $request->sid;
            $response[ 'sms_application_sid' ]    = $request->smsApplicationSid;
            $response[ 'sms_fallback_method' ]    = $request->smsFallbackMethod;
            $response[ 'sms_method' ]             = $request->smsMethod;
            $response[ 'sms_url' ]                = $request->smsUrl;
            $response[ 'status_callback' ]        = $request->statusCallback;
            $response[ 'status_callback_method' ] = $request->statusCallbackMethod;
            $response[ 'voice_application_sid' ]  = $request->voiceApplicationSid;
            $response[ 'voice_caller_id_lookup' ] = $request->voiceCallerIdLookup;
            $response[ 'voice_fallback_method' ]  = $request->voiceFallbackMethod;
            $response[ 'voice_fallback_url' ]     = $request->voiceFallbackUrl;
            $response[ 'voice_method' ]           = $request->voiceMethod;
            $response[ 'voice_url' ]              = $request->voiceUrl;
            $response[ 'capabilities' ]           = $request->capabilities;
        } catch ( TwilioException $exception ) {

            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * Update number
     *
     * @param       $numberSid
     * @param array $config
     *
     * @return \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberInstance|null
     */
    public function updateNumber( $numberSid, $config = [] )
    {

        $response = null;
        try {
            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );

            $incomingPhoneNumbers = $client->incomingPhoneNumbers( $numberSid )
                                           ->update( $config );

            $response = $incomingPhoneNumbers;
        } catch ( TwilioException $exception ) {
            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * Release number from Signalwire
     *
     * @param       $numberSid
     *
     * @return bool
     */
    public function releaseNumber( $numberSid )
    {
        $response = null;
        try {

            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $response                          = $client->incomingPhoneNumbers( $numberSid )
                                                        ->delete();
        } catch ( TwilioException $exception ) {
            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * Release number from Signalwire
     *
     * @return bool
     */
    public function listAccounts()
    {
        $response = null;
        try {

            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $accounts                          = $client->api->v2010->accounts->read();
            $counter                           = 0;
            foreach ( $accounts as $account ) {
                $response[ $counter ][ 'friendly_name' ]     = $account->friendlyName;
                $response[ $counter ][ 'owner_account_sid' ] = $account->ownerAccountSid;
                $response[ $counter ][ 'account_sid' ]       = $account->sid;
                $response[ $counter ][ 'status' ]            = $account->status;
                $response[ $counter ][ 'type' ]              = $account->type;
                $counter ++;
            }
        } catch ( TwilioException $exception ) {
            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * Fetch account details
     *
     * @return null| array |TwilioException
     */
    public function fetchAccountDetails()
    {
        $response = null;
        try {

            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $account                           = $client->api->v2010->accounts( $this->projectId )->fetch();
            $response[ 'friendly_name' ]       = $account->friendlyName;
            $response[ 'owner_account_sid' ]   = $account->ownerAccountSid;
            $response[ 'account_sid' ]         = $account->sid;
            $response[ 'status' ]              = $account->status;
            $response[ 'type' ]                = $account->type;
        } catch ( TwilioException $exception ) {

            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * update account details
     *
     * @param $accountSid
     *
     * @return null| array |TwilioException
     */
    public function updateAccount( $accountSid, array $details )
    {
        $response = null;
        try {
            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $account                           = $client->api->v2010->accounts( $accountSid )->update(
                $details
            );

            $response[ 'friendly_name' ]     = $account->friendlyName;
            $response[ 'owner_account_sid' ] = $account->ownerAccountSid;
            $response[ 'account_sid' ]       = $account->sid;
            $response[ 'status' ]            = $account->status;
            $response[ 'type' ]              = $account->type;
        } catch ( TwilioException $exception ) {
            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }

    /**
     * Fetch account dids list
     *
     * @return null| array |TwilioException
     */
    public function listAccountNumbers()
    {
        $response = null;
        try {

            $_ENV[ 'SIGNALWIRE_API_HOSTNAME' ] = $this->spaceUrl;
            $client                            = new Client( $this->projectId, $this->authToken );
            $incomingPhoneNumbers              = $client->incomingPhoneNumbers->read();
            $counter                           = 0;
            foreach ( $incomingPhoneNumbers as $number ) {

                $response[ $counter ][ 'number_sid' ]             = $number->sid;
                $response[ $counter ][ 'account_sid' ]            = $number->accountSid;
                $response[ $counter ][ 'phone_number' ]           = $number->phoneNumber;
                $response[ $counter ][ 'friendly_name' ]          = $number->friendlyName;
                $response[ $counter ][ 'sms_application_sid' ]    = $number->smsApplicationSid;
                $response[ $counter ][ 'sms_fallback_method' ]    = $number->smsFallbackMethod;
                $response[ $counter ][ 'sms_method' ]             = $number->smsMethod;
                $response[ $counter ][ 'sms_url' ]                = $number->smsUrl;
                $response[ $counter ][ 'status_callback' ]        = $number->statusCallback;
                $response[ $counter ][ 'status_callback_method' ] = $number->statusCallbackMethod;
                $response[ $counter ][ 'voice_application_sid' ]  = $number->voiceApplicationSid;
                $response[ $counter ][ 'voice_caller_id_lookup' ] = $number->voiceCallerIdLookup;
                $response[ $counter ][ 'voice_fallback_method' ]  = $number->voiceFallbackMethod;
                $response[ $counter ][ 'voice_fallback_url' ]     = $number->voiceFallbackUrl;
                $response[ $counter ][ 'voice_method' ]           = $number->voiceMethod;
                $response[ $counter ][ 'voice_url' ]              = $number->voiceUrl;
                $response[ $counter ][ 'capabilities' ]           = $number->capabilities;

                $counter ++;
            }

        } catch ( TwilioException $exception ) {

            $response[ 'error_code' ]    = $exception->getCode();
            $response[ 'error_message' ] = $exception->getMessage();
        }

        return $response;
    }
}

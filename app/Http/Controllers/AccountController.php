<?php

namespace App\Http\Controllers;

use App\Http\SourceProviders\SignalWire;
use App\VoipAccount;
use App\VoipNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {

        $counter  = 0;
        $accounts = VoipAccount::orderBy( 'updated_at', 'desc' )->get();

        return view( 'account.index', compact( 'accounts', 'counter' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view( 'account.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store( Request $request )
    {
        //return $request->all();
        // validate
        $this->validate( $request, [
            'friendly_name'      => 'required',
            'project_id'         => 'required',
            'space_url'          => 'required',
            'api_auth_token'     => 'required',
            'voice_url'          => 'nullable|url',
            'voice_fallback_url' => 'nullable|url',
            'status'             => 'required',
        ] );

        // create account
        $account                     = new VoipAccount();
        $account->user_id            = auth()->id();
        $account->friendly_name      = $request->friendly_name;
        $account->account_sid        = (string) Str::uuid();
        $account->status             = $request->status;
        $account->project_id         = $request->project_id;
        $account->space_url          = $request->space_url;
        $account->api_auth_token     = $request->api_auth_token;
        $account->type               = 'dummy';
        $account->total_numbers      = 0;
        $account->voice_url          = $request->voice_url;
        $account->voice_fallback_url = $request->voice_fallback_url;
        $account->save();

        // verify account with SignalWired
        $response = ( new SignalWire( auth()->id(), $account->id ) )->fetchAccountDetails();
        if ( isset( $response[ 'error_code' ] ) ) {

            // delete record
            $account->delete();

            // set success message
            $request->session()->flash( 'alert-danger', $response[ 'error_message' ] );

            // redirect back
            return redirect()->back();
        }

        // account is valid and exists on SignalWire so lets update it again with real data
        $updateAccount              = VoipAccount::find( $account->id );
        $updateAccount->account_sid = $response[ 'account_sid' ];
        $updateAccount->type        = $response[ 'type' ];
        $updateAccount->status      = $response[ 'status' ];
        $updateAccount->save();

        // set success message
        $request->session()->flash( 'alert-success', 'Account have been created successfully!' );

        // redirect back
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit( $id )
    {
        $account = VoipAccount::find( $id );

        return view( 'account.edit', compact( 'account' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param integer                  $accountId Twilio Sub Account ID
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update( Request $request, $accountId )
    {
        //return $request->all();
        // validate
        $this->validate( $request, [
            'friendly_name'      => 'required',
            'project_id'         => 'required',
            'space_url'          => 'required',
            'api_auth_token'     => 'required',
            'voice_url'          => 'nullable|url',
            'voice_fallback_url' => 'nullable|url',
        ] );

        // verify account with SignalWire
        $response = ( new SignalWire( auth()->id(), $accountId ) )->fetchAccountDetails();
        if ( isset( $response[ 'error_code' ] ) ) :
            // set failed message
            $request->session()->flash( 'alert-danger', $response[ 'error_message' ] );

            // redirect back
            return redirect()->back();
        endif;

        /**
         * In SignalWire we do not have any sub account feature, so we are actually handling numbers by local account
         * It means that we are grouping them by local accounts and update all the numbers associated with that
         * account
         */
        $account = VoipAccount::find( $accountId );

        // update sub account SignalWire numbers urls
        $this->updateAccountNumbers( $request, $accountId );

        // update friendly name on SignalWire
        ( new SignalWire( auth()->id(), $accountId ) )->updateAccount( $account->account_sid, [
            "FriendlyName" => $request->friendly_name,
            'status'       => $request->status,
        ] );

        // update account
        $account->user_id            = auth()->id();
        $account->friendly_name      = $request->friendly_name;
        $account->status             = $response[ 'status' ];
        $account->type               = $response[ 'type' ];
        $account->total_numbers      = $this->countAccountNumbers( $accountId );
        $account->voice_url          = $request->voice_url;
        $account->voice_fallback_url = $request->voice_fallback_url;
        $account->save();

        // set success message
        $request->session()->flash( 'alert-success', 'Account have been updated successfully!' );

        // redirect back
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage. Called from the accounts view
     * to update all the accounts and their numbers information
     *
     * @param \Illuminate\Http\Request $request
     * @param integer                  $userId Twilio Master User Account ID
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function syncAccounts( Request $request, $userId )
    {

        // get the sub accounts
        $twilio   = new TwilioController( $userId, 'master' );
        $accounts = $twilio->listAllAccounts();

        //return $accounts;
        // if sub account sid and sub account auth token finds in the model
        // then update its info like title and numbers count
        // otherwise this is a new account lets
        // save it into model
        foreach ( $accounts as $account ):

            $status = ( $account[ 'status' ] == 'active' ) ? 1 : 0;

            VoipAccount::updateOrCreate( [
                'account_sid' => $account[ 'account_sid' ],
                'auth_token'  => $account[ 'auth_token' ],
            ], [
                'user_id'            => auth()->id(),
                'account_sid'        => $account[ 'account_sid' ],
                'auth_token'         => $account[ 'auth_token' ],
                'title'              => $account[ 'title' ],
                'type'               => $account[ 'type' ],
                'status'             => $status,
                'account_created_at' => $account[ 'created_at' ]->format( 'Y-m-d H:i:s' ),
                'account_updated_at' => $account[ 'updated_at' ]->format( 'Y-m-d H:i:s' ),
            ] );

            // the account has been stored or updated in our database and now
            // we have its sid and auth token. So lets make another query
            // to Twilio and get the count of numbers present in this
            // Twilio account and then update database again. I'm
            // doing this because for new accounts we do not
            // have their sid and auth token and get an
            // error while updating their numbers
            // count. Only for active accounts.
            if ( $status == true ) :
                VoipAccount::updateOrCreate( [
                    'account_sid' => $account[ 'account_sid' ],
                    'auth_token'  => $account[ 'auth_token' ],
                ], [
                    //'total_numbers' => $this->countAccountNumbers( $account[ 'account_sid' ], $account[ 'auth_token' ] ),
                ] );

            endif;

        endforeach;

        // set success message
        $request->session()->flash( 'alert-success', 'Accounts have been updated successfully!' );

        // redirect back
        return redirect()->back();
    }

    /**
     * Get the total numbers exists in an account
     *
     * @param $accountId
     *
     * @return int
     */
    public function countAccountNumbers( $accountId )
    {
        // get account id by its account sid and auth token
        $account = VoipAccount::find( $accountId );
        $numbers = $account->numbers;

        return count( $numbers );
    }

    /**
     * Remove the specified resource from storage.
     *
     * Close Twilio account, remove its number from application and finally delete it
     * from applications
     *
     * Only free account can be closed and removed
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy( $id )
    {

        $account = VoipAccount::find( $id );

        // If in used by business then do not delete it. Only free account can be closed and removed
        if ( ! is_null( $account->business_id ) ) {

            // set success message
            \request()->session()->flash( 'alert-danger', 'FAILED: This account has attached with a business. To close this
			account please make it free first and then try again,' );

            // redirect back
            return redirect()->back();
        }

        // close account and release its DID from SignalWire
        $this->releaseAccountNumbers( $account->numbers );

        // delete account related numbers from storage
        VoipNumber::where( 'voip_account_id', $id )->delete();

        // delete account from storage
        VoipAccount::destroy( $id );

        // set success message
        \request()->session()->flash( 'alert-success', 'Account have been closed successfully!' );

        // redirect back
        return redirect()->back();
    }

    /**
     * Update SignalWire account numbers
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $accountId
     */
    public function updateAccountNumbers( Request $request, $accountId )
    {

        // fetch account numbers by its id
        $account    = VoipAccount::find( $accountId );
        $numbers    = $account->numbers;
        $signalWire = new SignalWire( auth()->id(), $accountId );

        if ( ! empty( $numbers ) ) {
            foreach ( $numbers as $number ) :

                // set the attributes array
                $numberAttributes = [
                    "voiceUrl"         => $request->voice_url,
                    "voiceFallbackUrl" => $request->voice_fallback_url,
                ];

                if ( isset( $request->voice_status_change_callback_url ) and ! is_null( $request->voice_status_change_callback_url ) ) {
                    $numberAttributes[ 'StatusCallback' ] = $request->voice_status_change_callback_url;
                }
                // update on SignalWire
                $signalWire->updateNumber( $number->number_sid, $numberAttributes );

                // update in database
                VoipNumber::where( 'number_sid', $number->number_sid )->update( [
                    'voice_url'          => $request->voice_url,
                    'voice_fallback_url' => $request->voice_fallback_url,
                ] );

            endforeach;
        }
    }

    /**
     * Delete numbers from Signalwire API
     *
     * @param $numbers
     */
    public function releaseAccountNumbers( $numbers )
    {

        $signalWire = new SignalWire( auth()->id() );
        if ( ! empty( $numbers ) ) {

            foreach ( $numbers as $number ) :

                // update on Twilio
                $signalWire->releaseNumber( $number->number_sid );

            endforeach;
        }
    }
}

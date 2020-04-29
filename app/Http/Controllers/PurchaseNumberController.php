<?php

namespace App\Http\Controllers;

use App\Http\SourceProviders\SignalWire;
use App\VoipAccount;
use App\VoipNumber;
use Illuminate\Http\Request;

class PurchaseNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $accounts = VoipAccount::all();

        return view( 'purchase_number.search', compact( 'accounts' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function create( Request $request )
    {
        //return $request->all();
        // Setting validation rules
        $rules = [
            'voip_account_id'    => 'required|numeric',
            'search_number_type' => 'required',
            'capabilities'       => 'required',
            'number_type'        => 'required',
        ];
        if ( $request->search_number_type == 'location' ) {
            $rules[ 'search_by_location' ] = 'required';
        } elseif ( $request->search_number_type == 'postal_code_file' ) {
            $rules[ 'postal_codes_file' ] = 'required|file|mimetypes:text/csv,text/plain,text/tsv';
        } else {
            $rules[ 'area_codes_file' ] = 'required|file|mimetypes:text/csv,text/plain,text/tsv';
        }

        // validate
        $this->validate( $request, $rules );

        // in any type of search, lets call a search method which
        // will return results as per user criteria
        $accountId        = $request->voip_account_id;
        $availableNumbers = ( new SearchNumberController( $accountId ) )->searchNumbers( $request );
        $counter          = 0;

        return view( 'purchase_number.list_numbers', compact( 'availableNumbers', 'counter', 'accountId' ) );
    }

    /**
     * Store a newly purchased Twilio number in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        //return $request->all();

        // we have strings of number lets explode this
        $numbers        = explode( ',', $request->numbers_to_purchase );
        $signalWire     = new SignalWire( auth()->id(), $request->account_id );
        $skippedNumbers = [];

        if ( is_array( $numbers ) and ! empty( $numbers ) ):

            foreach ( $numbers as $number ) :

                // purchase number
                $purchasedNumber = $signalWire->purchaseNumber( $number, $request->account_id );

                // skipp if there is a purchase error or exception
                if ( isset( $purchasedNumber[ 'error_code' ] ) ) {
                    $skippedNumbers[] = $number;
                    continue;
                }
                // we need check that number sid is already exists in database or not
                // if it is not then lets purchase the number and save it into database
                VoipNumber::updateOrCreate( [
                    'number_sid' => $purchasedNumber[ 'number_sid' ],
                ], [
                    'user_id'                => auth()->id(),
                    'voip_account_id'        => $purchasedNumber[ 'voip_account_id' ],
                    'friendly_name'          => $purchasedNumber[ 'friendly_name' ],
                    'phone_number'           => $purchasedNumber[ 'phone_number' ],
                    'number_sid'             => $purchasedNumber[ 'number_sid' ],
                    'account_sid'            => $purchasedNumber[ 'account_sid' ],
                    'sms_application_sid'    => $purchasedNumber[ 'sms_application_sid' ],
                    'sms_fallback_method'    => $purchasedNumber[ 'sms_fallback_method' ],
                    'sms_method'             => $purchasedNumber[ 'sms_method' ],
                    'sms_url'                => $purchasedNumber[ 'sms_url' ],
                    'status_callback'        => $purchasedNumber[ 'status_callback' ],
                    'status_callback_method' => $purchasedNumber[ 'status_callback_method' ],
                    'voice_application_sid'  => $purchasedNumber[ 'voice_application_sid' ],
                    'voice_caller_id_lookup' => $purchasedNumber[ 'voice_caller_id_lookup' ],
                    'voice_fallback_method'  => $purchasedNumber[ 'voice_fallback_method' ],
                    'voice_fallback_url'     => $purchasedNumber[ 'voice_fallback_url' ],
                    'voice_method'           => $purchasedNumber[ 'voice_method' ],
                    'voice_url'              => $purchasedNumber[ 'voice_url' ],
                    'capabilities'           => $purchasedNumber[ 'capabilities' ],
                ] );

            endforeach;
        endif;

        // get the total number in database against this
        // account update counter for accounts
        $numbersCount = VoipNumber::where( 'voip_account_id', $request->account_id )->count();
        VoipAccount::where( 'id', $request->account_id )->update( [ 'total_numbers' => $numbersCount ] );

        // success messages
        \request()->session()->flash( 'alert-success', 'Number has been purchased successfully!' );

        // redirect back
        return redirect()->route( 'voip-accounts.index' );
    }
}

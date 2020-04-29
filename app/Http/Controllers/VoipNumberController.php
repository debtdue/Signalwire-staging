<?php

namespace App\Http\Controllers;

use App\Http\SourceProviders\SignalWire;
use App\VoipAccount;
use App\VoipNumber;
use Illuminate\Http\Request;

class VoipNumberController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show( $id )
    {
        // i have an account id
        $account = VoipAccount::find( $id );

        $numbers = $account->numbers;
        $counter = 0;

        return view( 'voip_numbers.show', compact( 'account', 'numbers', 'counter' ) );
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
        $number = VoipNumber::find( $id );

        return view( 'voip_numbers.edit', compact( 'number' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update( Request $request, $id )
    {
        //return $request->all();
        // do validation
        $this->validate( $request, [
            'friendly_name'      => 'required',
            'voice_url'          => 'required',
            'voice_fallback_url' => 'required',
        ] );

        // get number sid and account id
        $number = VoipNumber::find( $id );

        // set the attributes array
        $numberAttributes = [
            "friendlyName"     => $request->friendly_name,
            "voiceUrl"         => $request->voice_url,
            "voiceFallbackUrl" => $request->voice_fallback_url,
        ];

        // update SignalWire
        $signalWire = new SignalWire( auth()->id(), $number->voip_account_id );
        $signalWire->updateNumber( $number->number_sid, $numberAttributes );

        // update database
        VoipNumber::where( 'id', $id )->update( [
            'friendly_name'      => $request->friendly_name,
            'voice_url'          => $request->voice_url,
            'voice_fallback_url' => $request->voice_fallback_url,
        ] );

        // set success message
        $request->session()->flash( 'alert-success', 'Number has been updated successfully!' );

        // redirect back
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * Remove the number from application and SignalWire sub account
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        // get number details
        $number = VoipNumber::find( $id );

        // release number from SignalWire account
        $signalWire         = new SignalWire( auth()->id(), $number->voip_account_id );
        $signalWireResponse = $signalWire->releaseNumber( $number->number_sid );

        if ( is_array( $signalWireResponse ) and isset( $signalWireResponse[ 'is_exception' ] ) ):

            // set success message
            \request()->session()->flash( 'alert-danger', $signalWireResponse[ 'message' ] );

        else:
            // delete number from storage
            VoipNumber::destroy( $id );

            // get the total number in database against this
            // account update counter for accounts
            $numbersCount = VoipNumber::where( 'voip_account_id', $number->voip_account_id  )->count();
            VoipAccount::where( 'id', $number->voip_account_id  )->update( [ 'total_numbers' => $numbersCount ] );

            // set success message
            \request()->session()->flash( 'alert-success', 'Number has been removed from application and SignalWire account successfully!' );

        endif;

        // redirect back
        return redirect()->back();
    }

    /**
     * Update Account numbers if exists otherwise insert them
     *
     * @param \App\VoipAccount $account
     *
     * @return array|\Exception|\Illuminate\Http\RedirectResponse|\Twilio\Exceptions\ConfigurationException
     */
    public function updateAccountNumbers( VoipAccount $account )
    {

        $twilio  = new SignalWire( auth()->id(), $account->id );
        $numbers = $twilio->listAccountNumbers();

        foreach ( $numbers as $number ):
            //dd($number);
            // if sub account sid and sub account auth token finds in the model
            // then update its info like title and numbers count
            // otherwise this is a new account lets
            // save it into model
            $data = [
                'user_id'               => auth()->id(),
                'voip_account_id'       => $account->id,
                'account_sid'           => $number[ 'account_sid' ],
                'number_sid'            => $number[ 'number_sid' ],
                'phone_number'          => $number[ 'phone_number' ],
                'friendly_name'         => $number[ 'friendly_name' ],
                'voice_fallback_method' => $number[ 'voice_fallback_method' ],
                'voice_fallback_url'    => $number[ 'voice_fallback_url' ],
                'voice_method'          => $number[ 'voice_method' ],
                'voice_url'             => $number[ 'voice_url' ],
                'capabilities'          => $number[ 'capabilities' ],
                'status'                => 1,
            ];

            VoipNumber::updateOrCreate( [
                'number_sid' => $number[ 'number_sid' ],
            ], $data );

        endforeach;

        // update the total counts in accounts
        $totalNumbers           = $account->numbers()->count();
        $account->total_numbers = $totalNumbers;
        $account->save();

        // set success message
        \request()->session()->flash( 'alert-success', 'Numbers have been updated for this account successfully!' );

        // redirect back
        return redirect()->back();
    }
}

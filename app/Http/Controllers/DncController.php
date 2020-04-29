<?php

namespace App\Http\Controllers;

use App\DncNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DncController extends Controller{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$numbers = DncNumber::orderBy( 'created_at', 'desc' )->get();
		$counter = 0;

		return view( 'dnc.index', compact( 'numbers', 'counter' ) );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request )
	{
		// Setting validation rules
		$rules = [
			'dnc_method'        => 'required',
			'dnc_greeting_file' => 'nullable|file|mimetypes:audio/mpeg,wav',
		];

		if ( $request->dnc_method == 'add_manually' ) :
			$rules['manual_add'] = 'required';
		elseif ( $request->dnc_method == 'add_wild_card' ) :
			$rules['wild_card_add'] = 'required';
		else:
			$rules['dnc_csv'] = 'required|file|mimetypes:text/csv,text/plain,text/tsv';
		endif;

		$this->validate( $request, $rules );
		$inserts = [];

		// upload file
		if ( $request->dnc_method == 'upload_csv' and $request->hasFile( 'dnc_csv' ) ) {

			// process the file
			$inserts = $this->readCsv( $request->file( 'dnc_csv' ) );
		} else {

			// add manually
			$type    = ( $request->dnc_method == 'add_wild_card' ) ? 'wild_card' : 'number';
			$numbers = ( $request->dnc_method == 'add_wild_card' ) ? explode( ',', $request->wild_card_add ) : explode( ',', $request->manual_add );

			// upload dnc greeting file if any
			if ( $request->hasfile( 'dnc_greeting_file' ) ) {
				$request->file( 'dnc_greeting_file' )->storeAs( "public/dnc/greetings", 'dnc_greeting.mp3' );
				$greetingAudio = 'dnc_greeting.mp3';
			} else {
				$greetingAudio = null;
			}

			$array_counter = 0;
			foreach ( $numbers as $number ) :

				$inserts[ $array_counter ]['user_id']        = auth()->id();
				$inserts[ $array_counter ]['number']         = ( $request->dnc_method == 'add_wild_card' ) ? $number : cleanNumber( $number );
				$inserts[ $array_counter ]['blacklisted']    = 1;
				$inserts[ $array_counter ]['type']           = $type;
				$inserts[ $array_counter ]['greeting_audio'] = $greetingAudio;
				$inserts[ $array_counter ]['created_at']     = Carbon::now();
				$inserts[ $array_counter ]['updated_at']     = Carbon::now();
				$array_counter ++;
			endforeach;
		}

		// do a bulk insert making chunks to get saved from
		// prepared statement place holder error
		if ( count( $inserts ) > 1000 ) :
			foreach ( array_chunk( $inserts, 1000 ) as $chunk ) :
				DncNumber::insert( $chunk );
			endforeach;
		else:
			DncNumber::insert( $inserts );
		endif;

		// success message
		$request->session()->flash( 'alert-success', 'Successfully added in Do Not Contact List' );

		// redirect
		return redirect()->back();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id )
	{
		// delete business
		DncNumber::destroy( $id );

		// success messages
		\request()->session()->flash( 'alert-success', 'Number has been removed from DNC list successfully!' );

		// redirect back
		return redirect()->back();
	}

	/**
	 * Read CSV file, clean the numbers and return back an array
	 *
	 * @param $file
	 *
	 * @return array
	 */
	public function readCsv( $file )
	{
		$numbers       = [];
		$array_counter = 0;
		$csv           = array_map( 'str_getcsv', file( $file ) );

		foreach ( $csv as $key => $value ) :

			//dd(array_filter($value));
			if ( $key != 0 ) :

				// removing empty arrays
				$value = array_filter( $value );

				//
				if ( ! isset( $value[0] ) ) :
					continue;
				endif;

				$numbers[ $array_counter ]['user_id']     = auth()->id();
				$numbers[ $array_counter ]['number']      = cleanNumber( $value[0] );// making valid US number
				$numbers[ $array_counter ]['blacklisted'] = 1;
				$inserts[ $array_counter ]['created_at']  = Carbon::now();
				$inserts[ $array_counter ]['updated_at']  = Carbon::now();
				$array_counter ++;

			endif;
		endforeach;

		return $numbers;
	}

	/**
	 * Add a number into DNC list by an Ajax method
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return string
	 */
	public function addNumberInDnc( Request $request )
	{
		$dnc              = new DncNumber();
		$dnc->user_id     = auth()->id();
		$dnc->number      = cleanNumber($request->from_number);
		$dnc->blacklisted = 1;
		$dnc->type        = 'number';

		$dnc->save();

		return json_encode(true);
	}
}

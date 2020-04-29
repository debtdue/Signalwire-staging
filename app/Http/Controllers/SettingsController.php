<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller{
	/**
	 * Show settings view
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function show()
	{

		// get user settings
		$settings = auth()->user()->settings;

		//return $settings;

		return view( 'settings.index', compact( 'settings' ) );
	}

	/**
	 * Update settings
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( Request $request )
	{

		// validate inputs
		$validatedData = $request->validate( [
			'signal_wire_space_url' => 'required',
			'signal_wire_project_id' => 'required',
			'signal_wire_auth_token'  => 'required',
		] );

		// update or save settings
		Settings::updateOrCreate( [ 'user_id' => auth()->id() ], $validatedData );

		//return $flight;
		$request->session()->flash( 'alert-success', 'Success: Settings has been updated successfully' );

		// redirect back
		return redirect()->back();
	}
}

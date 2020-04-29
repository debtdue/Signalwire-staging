<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller{

	/**
	 * Show profile view
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function show()
	{

		$profile = auth()->user();

		return view( 'profile.index', compact( 'profile' ) );
	}

	/**
	 * Update profile
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update( Request $request )
	{

		$user     = User::find( auth()->id() );
		$password = ( null === $request->password ) ? $user->password : \Hash::make( $request->password );

		// validation
		$request->validate( [
			'name'     => 'required|min:3|max:50',
			'email'    => 'required|email|unique:users,email,' . $user->id,
			'password' => 'nullable|min:8|confirmed',
		] );

		// update
		$user->name     = $request->name;
		$user->email    = $request->email;
		$user->password = $password;
		$user->save();

		// set success message
		$request->session()->flash( 'alert-success', 'Profile has been updated successfully!' );

		// redirect back
		return redirect()->back();
	}

}

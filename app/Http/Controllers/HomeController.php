<?php

namespace App\Http\Controllers;

use App\Agent;
use App\Business;
use App\Call;
use App\VoipAccount;
use App\VoipNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware( 'auth' );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{

		$counts               = $this->getCounts();
		$last5Calls           = Call::orderBy( 'created_at', 'desc' )->take( 5 )->get();
		$currentMonthAvgCalls = $this->monthlyAvgCalls();

		//return $currentMonthAvgCalls;

		return view( 'dashboard.index', compact( 'counts', 'last5Calls', 'currentMonthAvgCalls' ) );
	}

	/**
	 * Get widgets count for dashboard
	 *
	 * @return array
	 */
	public function getCounts()
	{
		$counts                     = [];
		$counts['total_businesses'] = Business::all()->count();
		$counts['total_accounts']   = VoipAccount::all()->count();
		$counts['total_agents']     = Agent::all()->count();
		$counts['total_numbers']    = VoipNumber::all()->count();
		$counts['total_calls']      = Call::all()->count();

		return $counts;
	}

	/**
	 * Get current month avg calls
	 *
	 * @return mixed
	 */
	public function monthlyAvgCalls()
	{
		$month    = Carbon::now()->format( 'm' );
		$avgCalls = \DB::table( 'calls' )->whereMonth( 'created_at', $month )->avg( 'id' );

		return ceil( $avgCalls );
	}
}

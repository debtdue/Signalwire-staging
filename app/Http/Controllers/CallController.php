<?php

namespace App\Http\Controllers;

use App\Call;
use Illuminate\Http\Request;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $calls = Call::select( [ 'calls.*', 'dnc_numbers.blacklisted as is_blacklisted' ] )
                     ->orderBy( 'calls.updated_at', 'desc' )
                     ->leftJoin( 'dnc_numbers', 'calls.from', '=', 'dnc_numbers.number' )
                     ->paginate( 50 );

        $counter = $calls->firstItem();

        return view( 'calls.index', compact( 'calls', 'counter' ) );
    }
}

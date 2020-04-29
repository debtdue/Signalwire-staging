<?php

namespace App\Http\Controllers;

use App\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $agents = Agent::with( 'businesses' )->get();

        return view( 'agent.index', compact( 'agents' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view( 'agent.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store( Request $request )
    {

        // validation
        $request->validate( [
            'first_name'   => 'required|max:50',
            'last_name'    => 'required|max:50',
            'phone_number' => 'required|max:12|unique:agents,phone_number',
            'email'        => 'email|unique:agents,email|nullable',
        ] );

        // Save
        $agent               = new Agent();
        $agent->user_id      = auth()->id();
        $agent->first_name   = $request->first_name;
        $agent->first_name   = $request->first_name;
        $agent->last_name    = $request->last_name;
        $agent->phone_number = $request->phone_number;
        $agent->email        = $request->email;

        $agent->save();

        // set success message
        $request->session()->flash( 'alert-success', 'Agent has been created successfully!' );

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
         abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Agent $agent
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit( Agent $agent )
    {

        return view( 'agent.edit', compact( 'agent' ) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {

        //return $request->all();

        // validation
        $request->validate( [
            'first_name'   => 'required|max:50',
            'last_name'    => 'required|max:50',
            'phone_number' => 'required|max:15|unique:agents,phone_number,' . $id,
            'email'        => 'nullable|unique:agents,email,' . $id,
        ] );

        // update
        $agent               = Agent::find( $id );
        $agent->first_name   = $request->first_name;
        $agent->last_name    = $request->last_name;
        $agent->phone_number = makeInternalNumber( $request->phone_number );
        $agent->email        = $request->email;
        $agent->save();

        // set success message
        $request->session()->flash( 'alert-success', 'Agent has been updated successfully!' );

        // redirect back
        return redirect()->route( 'agents.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy( $id )
    {

        // delete an agent
        Agent::destroy( $id );

        // set success message
        \request()->session()->flash( 'alert-success', 'Agent has been deleted successfully!' );

        // redirect back
        return redirect()->route( 'agents.index' );
    }
}

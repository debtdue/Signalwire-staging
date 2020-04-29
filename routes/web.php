<?php
Route::get( '/', function () {
    return view( 'welcome' );
} );

Auth::routes();

/**
 * Authenticated Routes. Only access able to logged in users
 */
Route::group( [ 'middleware' => [ 'auth' ] ], function () {

    Route::get( '/home', 'HomeController@index' )->name( 'home' );

    /**
     * Profile
     */
    Route::get( '/profile', 'ProfileController@show' )->name( 'show_profile' );
    Route::post( '/profile', 'ProfileController@update' )->name( 'update_profile' );

    /**
     * Settings
     */
    Route::get( '/settings', 'SettingsController@show' )->name( 'show_settings' );
    Route::post( '/settings', 'SettingsController@update' )->name( 'update_settings' );

    /**
     * Businesses
     */
    Route::resource( '/businesses', 'BusinessController' );

    /**
     * Agents
     */
    Route::resource( '/agents', 'AgentController' );

    /**
     * Do Not Contact Numbers
     */
    Route::resource( '/dnc-numbers', 'DncController', [ 'except' => [ 'create', 'edit', 'update', 'show' ] ] );
    Route::post( '/dnc-numbers/ajax/calls/add', 'DncController@addNumberInDnc' )->name( 'dnc.ajax.call.store' );

    /**
     * VoIP Account
     */
    Route::resource( '/voip-accounts', 'AccountController' );
    Route::put( '/voip-accounts/{account}/sync', 'AccountController@syncAccounts' )->name( 'voip-accounts.sync' );

    /**
     * VoIP Numbers
     */
    Route::resource( '/voip-numbers', 'VoipNumberController', [ 'except' => [ 'create', 'index', 'store' ] ] );
    Route::put( '/voip-numbers/{account}/store-update', 'VoipNumberController@updateAccountNumbers' )->name( 'voip-numbers.store.update' );

    /**
     * Search and Purchase Numbers
     */
    Route::get( '/numbers/search', 'PurchaseNumberController@index' )->name( 'voip-numbers.search' );
    Route::post( '/numbers/search', 'PurchaseNumberController@create' )->name( 'voip-numbers.list' );
    Route::post( '/numbers/purchase', 'PurchaseNumberController@store' )->name( 'voip-numbers.purchase' );

    /**
     * Call Logs
     */
    Route::get( '/calls-logs', 'CallController@index' )->name( 'calls.logs' );

    /**
     * New Report Routes
     *
     */
    Route::get( '/reports', 'ReportController@index' )->name( 'reports.index' );
    Route::get( '/reports/search', 'ReportController@search' )->name( 'reports.search' );
    Route::get( '/report/call/export', 'ReportController@exportReport' )->name( 'report.export.csv' );
    Route::get( '/report/business/export', 'ReportController@exportBusinessReport' )->name( 'report.business.export.csv' );
    //Route::get( '/reports/{call}/detail', 'ReportController@show' )->name( 'reports.searched_call_details' );
} );

Route::get( '/test', function () {

    //dd(urldecode( '+1 (813) 296-2903' )); // it will replace + with space
    //dd(urldecode( '+18132962903' ));// it will replace + with space
    //dd( urldecode( '%2B15125217576' ) ); // it will replace %2B
    dd( makeInternalNumber(  '+1 (813) 296-2903' )  );

    //dd( urldecode( '+18132962903' ) );
    dd( makeInternalNumber(  '+18132962903' )  );
    dd( urldecode( '%2B15125217576' ) );
    $signalWire = new \App\Http\SourceProviders\SignalWire( auth()->id(), 9 );

    return $signalWire->searchAvailableNumbers( 'US', 'local', [
        "inRegion" => "FL",
        //"city" => "Orlando",
    ] );
} );

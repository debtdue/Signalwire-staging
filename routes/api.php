<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware( 'auth:api' )->get( '/user', function ( Request $request ) {
    return $request->user();
} );

Route::prefix( '/signal-wire' )->group( function () {

    Route::post( '/call/{business}/inbound', 'InboundCallController@handle' )->name( 'inbound-call.receive' );  // Done
    Route::post( '/call/{business}/inbound/gather', 'GatherActionUrlController@handle' )->name( 'inbound-call.gather' ); // Done
    Route::post( '/call/{business}/inbound/fallback', 'InboundCallFallbackUrlController@handle' )->name( 'inbound-call.fallbackUrl' ); // not in use
    Route::post( '/call/{business}/inbound/number/answer', 'NumberActionUrlController@handle' )->name( 'inbound-call.number-action-url' ); // Done
    Route::post( '/call/inbound/recording/status-callback', 'RecordingStatusCallbackUrlController@handle' )->name( 'inbound-call.recording-status-callback-url' ); // Done
    Route::post( '/call/{business}/inbound/voice-mail/action', 'VoicemailActionUrlController@handle' )->name( 'inbound-call.voicemail-recording-url' );  // Done
    Route::post( '/call/{business}/inbound/dial/action', 'DialActionUrlController@handle' )->name( 'inbound-call.dial-action-url' );
    Route::post( '/call/{business}/inbound/status-change', 'StatusChangeUrlController@handle' )->name( 'inbound-call.status-change-url' );
} );

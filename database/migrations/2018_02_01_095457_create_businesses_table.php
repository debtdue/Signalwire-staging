<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'businesses', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'user_id' )->comment( 'created by' );
			$table->integer( 'voip_account_id' )->nullable();
			$table->string( 'title', 100 );
			$table->text( 'description' );
			$table->time( 'call_duration' )->nullable()->comment( 'allowed time for calls' );
			$table->string( 'email', 100 )->nullable();
			$table->string( 'greeting_message_type', 20 );
			$table->text( 'greeting_text' )->nullable();
			$table->text( 'greeting_audio' )->nullable();
			$table->boolean( 'whisper_message' );
			$table->string( 'whisper_message_type', 20 );
			$table->text( 'whisper_message_text' )->nullable();
			$table->text( 'whisper_message_audio' )->nullable();
			$table->boolean( 'record_calls' )->default( 0 );
			$table->string( 'recording_disclaimer_type', 20 )->nullable();
			$table->text( 'recording_disclaimer_text' )->nullable();
			$table->text( 'recording_disclaimer_audio' )->nullable();
			$table->boolean( 'display_caller_number' )->default( 0 );

			$table->timestamps();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists( 'businesses' );
	}
}

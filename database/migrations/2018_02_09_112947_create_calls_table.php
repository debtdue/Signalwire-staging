<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'calls', function ( Blueprint $table ) {

			$table->increments( 'id' );
			$table->integer( 'business_id' )->index();
			$table->integer( 'voip_account_id' )->nullable();
			$table->string( 'agent_number',12 )->nullable();

			$table->string( 'call_sid' )->nullable();
			$table->string( 'dial_call_sid' )->nullable();
			$table->time( 'dial_call_duration' )->nullable()->index();
			$table->string( 'direction', 20 )->nullable();
			$table->string( 'dial_call_status', 20 )->nullable();
			$table->string( 'call_status', 20 )->nullable();

			$table->text( 'recording_url' )->nullable();
            $table->string( 'recording_sid' )->nullable();
            $table->time( 'recording_duration' )->nullable();
            $table->string( 'recording_status',25 )->nullable();
            $table->string( 'recording_type',25 )->nullable();

            $table->string( 'to', 15 )->nullable();
			$table->string( 'to_zip', 25 )->nullable();
			$table->string( 'to_city', 50 )->nullable();
			$table->string( 'to_state', 30 )->nullable();
			$table->string( 'to_country', 50 )->nullable();

			$table->string( 'from', 15 )->nullable();
			$table->string( 'from_zip', 25 )->nullable();
			$table->string( 'from_city', 50 )->nullable();
			$table->string( 'from_state', 30 )->nullable();
			$table->string( 'from_country', 50 )->nullable();

			$table->string( 'called', 15 )->nullable();
			$table->string( 'called_zip', 25 )->nullable();
			$table->string( 'called_city', 50 )->nullable();
			$table->string( 'called_state', 30 )->nullable();
			$table->string( 'called_country', 50 )->nullable();

			$table->string( 'caller', 15 )->nullable();
			$table->string( 'caller_zip', 25 )->nullable();
			$table->string( 'caller_city', 50 )->nullable();
			$table->string( 'caller_state', 30 )->nullable();
			$table->string( 'caller_country', 50 )->nullable();

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
		Schema::dropIfExists( 'calls' );
	}
}

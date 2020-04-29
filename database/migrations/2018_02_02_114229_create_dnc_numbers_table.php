<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDncNumbersTable extends Migration{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create( 'dnc_numbers', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->integer( 'user_id' );
			$table->string( 'number', 16 );
			$table->boolean( 'blacklisted' );
			$table->text( 'greeting_audio' )->nullable();
			$table->string( 'type', 10 )->default( 'number' );
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
		Schema::dropIfExists( 'dnc_numbers' );
	}
}

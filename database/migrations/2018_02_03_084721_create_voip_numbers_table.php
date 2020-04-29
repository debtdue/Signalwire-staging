<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoipNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'voip_numbers', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'user_id' )->unsigned();
            $table->integer( 'voip_account_id' )->unsigned();
            $table->integer( 'business_id' )->nullable();
            $table->string( 'account_sid' )->nullable();
            $table->string( 'number_sid' )->unique();
            $table->string( 'phone_number' )->unique();
            $table->string( 'friendly_name' )->nullable();
            $table->string( 'sms_application_sid' )->nullable();
            $table->string( 'sms_fallback_method' )->nullable();
            $table->string( 'sms_method' )->nullable();
            $table->string( 'sms_url' )->nullable();
            $table->string( 'status_callback' )->nullable();
            $table->string( 'status_callback_method' )->nullable();
            $table->string( 'voice_application_sid' )->nullable();
            $table->string( 'voice_caller_id_lookup' )->nullable();
            $table->string( 'voice_fallback_method' )->nullable();
            $table->string( 'voice_fallback_url' )->nullable();
            $table->string( 'voice_status_change_callback_url' )->nullable();
            $table->string( 'voice_method' )->nullable();
            $table->string( 'voice_url' )->nullable();
            $table->string( 'capabilities' )->nullable();
            $table->string( 'status' )->default( 1 );
            $table->timestamps();

            $table->index( [ 'user_id', 'number_sid', 'phone_number' ] );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'voip_numbers' );
    }
}

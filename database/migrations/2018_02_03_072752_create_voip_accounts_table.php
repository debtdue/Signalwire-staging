<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoipAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'voip_accounts', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'user_id' )->unsigned();
            $table->integer( 'business_id' )->nullable();
            $table->string( 'account_sid' )->unique();
            $table->string( 'project_id' );
            $table->string( 'friendly_name' );
            $table->string( 'space_url' );
            $table->string( 'api_auth_token' );
            $table->string( 'type' );
            $table->text( 'voice_url' )->nullable();
            $table->text( 'voice_fallback_url' )->nullable();
            $table->text( 'voice_status_change_callback_url' )->nullable();
            $table->integer( 'total_numbers' )->default( 0 );
            $table->string( 'status',50 );
            $table->timestamps();

            $table->index( [ 'user_id', 'account_sid', 'business_id' ] );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'voip_accounts' );
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'settings', function ( Blueprint $table ) {
            $table->increments( 'id' );
            $table->integer( 'user_id' )->unique();
            $table->string( 'signal_wire_space_url' )->nullable();
            $table->string( 'signal_wire_project_id' )->nullable();
            $table->string( 'signal_wire_auth_token' )->nullable();
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
        Schema::dropIfExists( 'settings' );
    }
}

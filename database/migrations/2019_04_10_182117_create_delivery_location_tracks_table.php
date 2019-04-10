<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryLocationTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_location_tracks', function (Blueprint $table) {
            $table->integer('delivery_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->integer('step');

            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->foreign('location_id')->references('id')->on('locations');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_location_tracks');
    }
}

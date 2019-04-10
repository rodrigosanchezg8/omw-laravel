<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('delivery_man_id')->unsigned();
            $table->integer('sender_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->date('planned_start_date');
            $table->date('planned_end_date');
            $table->date('departure_date')->nullable();
            $table->date('arrival_date');
            $table->integer('departure_location_id')->unsigned();
            $table->integer('arrival_location_id')->unsigned();
            $table->integer('delivery_status_id')->unsigned()->nullable();

            $table->foreign('delivery_man_id')->references('id')->on('users');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->foreign('departure_location_id')->references('id')->on('locations');
            $table->foreign('arrival_location_id')->references('id')->on('locations');
            $table->foreign('delivery_status_id')->references('id')->on('delivery_statuses');

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
        Schema::dropIfExists('deliveries');
    }
}

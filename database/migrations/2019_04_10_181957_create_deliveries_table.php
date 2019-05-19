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
            $table->integer('delivery_man_id')->nullable()->unsigned();
            $table->integer('sender_id')->unsigned();
            $table->integer('receiver_id')->unsigned();
            $table->boolean('company_is_sending')->default(0);
            $table->dateTime('planned_start_date')->nullable();
            $table->dateTime('planned_end_date')->nullable();
            $table->dateTime('departure_date')->nullable();
            $table->dateTime('arrival_date')->nullable();
            $table->integer('delivery_status_id')->unsigned()->default(1);
            $table->decimal('score')->nullable();
            $table->text('comment_by_client')->nullable();
            $table->decimal('distance_in_km')->nullable();

            $table->foreign('delivery_man_id')->references('id')->on('delivery_men');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
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

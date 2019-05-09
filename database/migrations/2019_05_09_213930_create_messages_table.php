<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body');
            $table->unsignedInteger('delivery_id');
            $table->unsignedInteger('user_id_replier');
            $table->unsignedInteger('user_id_receiver');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries');
            $table->foreign('user_id_replier')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}

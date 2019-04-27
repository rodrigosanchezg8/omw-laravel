<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_products', function (Blueprint $table) {
            $table->increments('id')->first();
            $table->integer('delivery_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount');
            $table->decimal('cost');

            $table->foreign('delivery_id')->references('id')->on('deliveries');

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
        Schema::dropIfExists('delivery_products');
    }
}

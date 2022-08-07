<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_cd')->unique();
            $table->foreignId('user_id')->constrained();
            $table->integer('item_total');
            $table->bigInteger('hrg_subtotal');
            $table->string('discount')->nullable();
            $table->text('terms_discount')->nullable();
            $table->bigInteger('hrg_grandtotal')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

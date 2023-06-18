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
            $table->foreignIdFor(\App\Models\Products::class, 'product');
            $table->foreignIdFor(\App\Models\User::class, 'user');
            $table->foreignIdFor(\App\Models\User::class, 'rider')->nullable();
            $table->integer('quantity');
            $table->float('payment', 8, 2);
            $table->longText('note')->nullable();
            $table->string('place')->nullable();
            $table->string('time');
            $table->string('status');
            $table->string('type');
            $table->string('payment_method');
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
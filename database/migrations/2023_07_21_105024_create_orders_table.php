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
            $table->string('unique_id');
            $table->integer('customer_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('address')->nullable();
            $table->date('order_date')->nullable();
            $table->time('order_time')->nullable();
            $table->text('short_notes')->nullable();
            $table->enum('payment_type',['cod','paypal','gcash'])->nullable();
            $table->string('transaction_id')->nullable();
            $table->double('order_total')->nullable();
            $table->enum('status',['pending','paid','reject'])->default('pending');
            $table->softDeletes();
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

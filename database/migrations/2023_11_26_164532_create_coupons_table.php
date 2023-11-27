<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_name', 191);
            $table->string('coupon_code', 191)->unique();
            $table->double('amount',15,2);
            $table->dateTime('expire_date');
            $table->smallInteger('status')->comment('1=>active,2=>inactive');
            $table->boolean('type')->default(0)->comment('0=>value,1=>percentage');
            $table->double('min_order_amount',15,2)->nullable();
            $table->double('max_order_amount',15,2)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}

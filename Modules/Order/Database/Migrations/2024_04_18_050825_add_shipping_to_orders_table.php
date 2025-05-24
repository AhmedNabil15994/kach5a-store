<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_type')->after('order_notes')->nullable();
            $table->string('shipping_date')->after('shipping_type')->index()->nullable();
            $table->string('shipping_time_from')->after('shipping_date')->index()->nullable();
            $table->string('shipping_time_to')->after('shipping_time_from')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {

        });
    }
}

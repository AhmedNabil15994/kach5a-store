<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTimesAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_times_availability', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delivery_time_id')->unsigned()->nullable();
            $table->string('from')->index();
            $table->string('to')->index();
            $table->foreign('delivery_time_id')->references('id')->on('delivery_times')->onDelete('cascade');
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
        Schema::dropIfExists('delivery_times_availability');
    }
}

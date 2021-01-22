<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ['IN_PROGRESS','PENDING', 'ENDED','CANCELLED']
        // $table->enum('payment_method',['CASH', 'CARD']);
        // ['REQUESTER', 'RECIPIENT']
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('recipient_name');
            $table->string('recipient_phone_number');
            $table->string('start_location');
            $table->string('end_location');
            $table->string('cost_of_trip');
            $table->smallInteger('payment_method');
            $table->smallInteger('trip_status');
            $table->string('package_description')->nullable();
            $table->string('current_location')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->unsignedBigInteger('rider_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamps();

            $table->foreign('rider_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}

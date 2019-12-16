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
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('current_location')->nullable();
            $table->string('start_location')->nullable();
            $table->string('end_location');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('cost_of_trip');
            $table->enum('trip_status',['IN_PROGRESS','PENDING', 'ENDED','CANCELLED'])->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone_number');
            $table->enum('who_pays',['REQUESTER', 'RECIPIENT']);
            $table->enum('payment_method',['CASH', 'CARD']);
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->timestamps();

    
            $table->foreign('rider_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('package_id')->references('id')->on('packages');
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

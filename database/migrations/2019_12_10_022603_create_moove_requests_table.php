<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMooveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moove_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('delivery_location');
            $table->string('pick_up_location')->nullable();
            $table->string('recipient_name');
            $table->string('cost_of_trip');
            $table->string('recipient_phone_number');
            $table->longText('package_description')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->enum('who_pays',['REQUESTER', 'RECIPIENT']);
            $table->timestamps();

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
        Schema::dropIfExists('moove_requests');
    }
}

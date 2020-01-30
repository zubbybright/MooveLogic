<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('package_description')->nullable();
            $table->enum('package_type',['FRAGILE', 'NOT_FRAGILE'])->nullable();
            $table->string('size')->nullable();
            $table->string('weight')->nullable();
            $table->enum('package_status', ['PENDING', 'NOT_DELIVERED', 'DELIVERED']);
            $table->unsignedBigInteger('customer_id');
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
        Schema::dropIfExists('packages');
    }
}

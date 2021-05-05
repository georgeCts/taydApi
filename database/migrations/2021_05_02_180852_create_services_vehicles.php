<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->string('marca');
            $table->string('color');
            $table->string('latitude');
            $table->string('altitude');
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicles_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_vehicles');
    }
}

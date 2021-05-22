<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesVehiclesDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_vehicles_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_vehicle_id');
            $table->unsignedBigInteger('vehicle_type_price_id');
            $table->timestamps();

            $table->foreign('service_vehicle_id')->references('id')->on('services_vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_vehicles_details');
    }
}

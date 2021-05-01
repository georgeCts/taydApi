<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTypesPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles_types_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->string('key');
            $table->string('name');
            $table->decimal('price', 6, 2);
            $table->timestamps();
            $table->boolean('active')->default(1);

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
        Schema::dropIfExists('vehicles_types_prices');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTypesPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_types_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('property_type_id');
            $table->string('key');
            $table->string('name');
            $table->decimal('price', 6, 2);
            $table->timestamps();
            $table->boolean('active')->default(1);

            $table->foreign('property_type_id')->references('id')->on('properties_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties_types_prices');
    }
}

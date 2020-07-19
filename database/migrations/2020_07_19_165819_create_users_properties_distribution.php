<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPropertiesDistribution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_properties_distribution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_property_id');
            $table->unsignedBigInteger('property_type_price_id');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->foreign('user_property_id')->references('id')->on('users_properties');
            $table->foreign('property_type_price_id')->references('id')->on('properties_types_prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_properties_distribution');
    }
}

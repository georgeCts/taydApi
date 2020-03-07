<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('latitude');
            $table->string('altitude');
            $table->integer('rooms_qty')->default(0);
            $table->integer('bathrooms_qty')->default(0);
            $table->boolean('has_living_room')->default(false);
            $table->boolean('has_dinning_room')->default(false);
            $table->boolean('has_kitchen')->default(false);
            $table->boolean('has_garage')->default(false);
            $table->boolean('has_backyard')->default(false);
            $table->integer('floors_qty')->default(1);
            $table->unsignedBigInteger('property_type_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('users_properties');
    }
}

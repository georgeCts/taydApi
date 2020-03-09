<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('request_user_id');
            $table->unsignedBigInteger('provider_user_id')->nullable();
            $table->unsignedBigInteger('user_property_id');
            $table->unsignedBigInteger('stripe_customer_source_id')->nullable();
            $table->boolean('is_accepted')->default(false);
            $table->dateTime('dt_start');
            $table->dateTime('dt_finish')->nullable();
            $table->boolean('has_consumables')->default(false);
            $table->decimal('cost', 6, 2);
            $table->boolean('is_canceled')->default(false);
            $table->dateTime('dt_canceled')->nullable();
            $table->timestamps();

            $table->foreign('request_user_id')->references('id')->on('users');
            $table->foreign('provider_user_id')->references('id')->on('users');
            $table->foreign('user_property_id')->references('id')->on('users_properties');
            $table->foreign('stripe_customer_source_id')->references('id')->on('stripe_customers_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}

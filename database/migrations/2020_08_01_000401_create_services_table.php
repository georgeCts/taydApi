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
            $table->unsignedBigInteger('user_property_id')->nullable();
            $table->unsignedBigInteger('stripe_customer_source_id')->nullable();
            $table->unsignedBigInteger('service_status_id');
            $table->dateTime('dt_request')->nullable();
            $table->dateTime('dt_start')->nullable();
            $table->dateTime('dt_finish')->nullable();
            $table->dateTime('dt_canceled')->nullable();
            $table->boolean('has_consumables')->default(false);
            $table->decimal('service_cost', 6, 2);
            $table->decimal('tax_service', 6, 2);
            $table->decimal('tayd_commission', 6, 2);
            $table->decimal('stripe_commission', 6, 2);
            $table->decimal('tax_stripe', 6, 2);
            $table->decimal('discount', 6, 2)->nullable();
            $table->decimal('total', 6, 2)->nullable();
            $table->string('charge_token')->nullable();
            $table->string('refund_token')->nullable();

            $table->integer('rating')->default(0);
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('request_user_id')->references('id')->on('users');
            $table->foreign('provider_user_id')->references('id')->on('users');
            $table->foreign('user_property_id')->references('id')->on('users_properties');
            $table->foreign('stripe_customer_source_id')->references('id')->on('stripe_customers_sources');
            $table->foreign('service_status_id')->references('id')->on('services_status');
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

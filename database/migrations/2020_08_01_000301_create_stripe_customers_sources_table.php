<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCustomersSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_customers_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_stripe_customer_id');
            $table->string('stripe_customer_source_token');
            $table->boolean('is_predetermined')->default(0);
            $table->timestamps();

            $table->foreign('user_stripe_customer_id')->references('id')->on('users_stripe_customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_customers_sources');
    }
}

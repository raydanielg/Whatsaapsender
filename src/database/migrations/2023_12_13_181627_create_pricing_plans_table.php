<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->json('gateway_credentials')->nullable();
            $table->decimal('amount', 18,8)->default(0.00000000);
            $table->integer('credit')->nullable();
            $table->integer('email_credit')->nullable();
            $table->integer('whatsapp_credit')->nullable();
            $table->integer('duration')->nullable();
            $table->tinyInteger('status')->default(0)->comment('Active: 1, Inactive: 2');
            $table->tinyInteger('recommended_status')->nullable()->comment('Active: 1, Inactive: 2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_plans');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->index()->nullable();
            $table->foreignId('user_id')->nullable();
            $table->string('type', 255)->nullable();
            $table->json('mail_gateways')->nullable();
            $table->json('sms_gateways')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->tinyInteger('status')->comment('Active: 1, Inactive: 0');
            $table->tinyInteger('is_default')->default(0);
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
        Schema::dropIfExists('gateways');
    }
}

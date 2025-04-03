<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWhatsappTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->index()->nullable();
            $table->integer('user_id')->nullable();
            $table->string('language_code', 32)->nullable();
            $table->string('name', 32)->nullable();
            $table->integer('cloud_id')->nullable();
            $table->string('category', 32)->nullable();
            $table->longText('template_information', 32)->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('whatsapp_templates');
    }
}

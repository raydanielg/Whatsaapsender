<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('uid',100)->index()->nullable();
            $table->foreignId('user_id')->nullable();
            $table->enum('type',[1,2,3])->comment('SMS: 1, WhatsApp: 2, Email: 3');
            $table->string('name');
            $table->string('slug');
            $table->longText('template_data');
            $table->json('meta_data')->nullable();
            $table->enum('plugin',[0,1])->comment('YES: 1, NO: 0');
            $table->enum('default',[0,1])->comment('YES: 1, NO: 0');
            $table->enum('global',[0,1])->comment('YES: 1, NO: 0');
            $table->enum('status',[0,1])->comment('Active: 1, Inactive: 0');
            $table->bigInteger('cloud_id')->comment('Whatsapp Cloud API')->nullable();
            $table->tinyInteger('provider')->nullable();
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
        Schema::dropIfExists('templates');
    }
}

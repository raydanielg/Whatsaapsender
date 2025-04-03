<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('uid',100)->index()->nullable();
            $table->enum('type',[1,2,3])->comment('SMS: 1, WhatsApp: 2, Email: 3');
            $table->foreignId('user_id')->nullable();
            $table->string('name');
            $table->integer('repeat_time')->nullable();
            $table->enum('repeat_format',[1,2,3,4])->nullable()->comment('Day: 1, Week: 2, Months: 3, Year: 4');
            $table->json('meta_data')->nullable();
            $table->enum('status', [1,2,3,4,5])->comment('Active: 1, Decative: 2, Completed: 3, Ongoing: 4, Cancel: 0');
            $table->dateTime('schedule_at')->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunicationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communication_logs', function (Blueprint $table) {
            
            $table->id();
            $table->string('uid', 32);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->enum('type', ['1', '2', '3'])->comment('SMS: 1, WhatsApp: 2, Email: 3');
            $table->unsignedBigInteger('gateway_id')->nullable();
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->enum('status', ['0','1','2','3','4','5'])->comment('Cancel: 0, Pending: 1, Schedule: 2, Fail: 3, Delivered: 4, Processing: 5');
            $table->json('message')->nullable();
            $table->json('meta_data')->nullable();
            $table->longText('response_message')->nullable();
            $table->unsignedBigInteger('android_gateway_sim_id')->nullable();
            $table->unsignedBigInteger('whatsapp_template_id')->nullable();
            $table->json('file_info')->nullable();
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
        Schema::dropIfExists('communication_logs');
    }
}

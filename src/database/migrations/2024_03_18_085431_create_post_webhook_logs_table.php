<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostWebhookLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('uid',100)->index()->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('webhook_response')->nullable();
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
        Schema::dropIfExists('post_webhook_logs');
    }
}

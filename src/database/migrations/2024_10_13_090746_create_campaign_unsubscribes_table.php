<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignUnsubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_unsubscribes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('contact_uid');
            $table->unsignedBigInteger('campaign_id');
            $table->enum('channel', [1, 2, 3])->comment('SMS: 1, WhatsApp: 2, Email: 3'); 
            $table->json('meta_data')->nullable();
            $table->timestamps();
            $table->index(['campaign_id', 'contact_uid', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_unsubscribes');
    }
}

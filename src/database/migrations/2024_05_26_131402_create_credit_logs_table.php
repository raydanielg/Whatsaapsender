<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('uid',100)->index()->nullable();
            $table->foreignId('user_id')->nullable();
            $table->enum('type',[1,2,3])->comment('SMS: 1, WhatsApp: 2, Email: 3');
            $table->enum('manual',[0,1])->comment('YES: 1, NO: 0');
            $table->string('trx_number',50)->unique();
            $table->string('details',255)->nullable();
            $table->enum('credit_type',[0,1])->comment('Plus: 1, Minus: 0');
            $table->integer('credit')->nullable();
            $table->integer('post_credit')->nullable();
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
        Schema::dropIfExists('credit_logs');
    }
}

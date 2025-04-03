<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            
            $table->id();
            $table->string('uid',100)->index()->nullable();
            $table->string('name',191)->unique();
            $table->string('code',100)->nullable();
            $table->enum('is_default',[0,1])->default(1)->comment('Yes: 1, No: 0');
            $table->enum('status',[0,1])->default(1)->comment('Active: 1, Deactive: 0');
            $table->enum('ltr',[0,1])->default(1)->comment('Yes: 1, No: 0');
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
        Schema::dropIfExists('languages');
    }
}

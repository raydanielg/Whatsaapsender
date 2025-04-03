<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_sections', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 32)->index()->nullable();
            $table->string('section_key', 190)->nullable();
            $table->text('section_value')->nullable();
            $table->tinyInteger('status')->default(1)->comment('Active: 1, Inactive: 2');
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
        Schema::dropIfExists('frontend_sections');
    }
}

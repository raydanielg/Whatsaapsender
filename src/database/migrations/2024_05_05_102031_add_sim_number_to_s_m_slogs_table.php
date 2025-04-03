<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSimNumberToSMSlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_m_slogs', function (Blueprint $table) {

            $table->string('sim_number')->nullable()->after('uid');
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_m_slogs', function (Blueprint $table) {

            Schema::dropIfExists('sim_number');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWaDevice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wa_device', function (Blueprint $table) {
           
            $table->longText('credentials')->after('admin_id')->nullable();
            $table->tinyInteger('type')->after('admin_id')->nullable()->comment("Whatsapp Node module: 0, Whatsapp Business API: 1");
            $table->string('uid', 32)->after('id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wa_device', function (Blueprint $table) {
           
           
            $table->dropColumn('credentials');
            $table->dropColumn('type');
            $table->dropColumn('uid');
        });
    }
}

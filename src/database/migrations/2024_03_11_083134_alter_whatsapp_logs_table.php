<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterWhatsappLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {

            DB::statement('ALTER TABLE whatsapp_logs MODIFY message LONGTEXT;');
            $table->tinyInteger('mode')->after('uid')->comment("Cloud API: 1, Node Module: 0")->nullable();
            $table->integer('template_id')->after('whatsapp_id')->nullable();
            $table->longText('message_response')->after('message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('whatsapp_logs', function (Blueprint $table) {
        
            DB::statement('ALTER TABLE whatsapp_logs MODIFY message TEXT;');
            $table->dropColumn('whatsapp_mode');
        });
    }
}

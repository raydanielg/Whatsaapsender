<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->longText('sms')->nullable()->after('name')->comment("SMS Information");
            $table->longText('email')->nullable()->after('name')->comment("Email Information");
            $table->longText('whatsapp')->nullable()->after('name')->comment("Whatsapp Information");
            $table->enum('carry_forward', [0,1])->nullable()->after('name')->comment("Enable: 1, Disable: 0");
            $table->enum('type', [0,1])->nullable()->after('name')->comment("Admin: 1, User: 0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
           
            $table->dropColumn('sms');
            $table->dropColumn('email');
            $table->dropColumn('whatsapp');
            $table->dropColumn('carry_forward');
            $table->dropColumn('type');
        });
    }
}

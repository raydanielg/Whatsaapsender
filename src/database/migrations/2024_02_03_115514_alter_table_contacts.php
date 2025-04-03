<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('uid', 32)->after('id')->index()->nullable();
            $table->string('first_name', 90)->after('name')->nullable();
            $table->string('last_name', 90)->after('name')->nullable();
            $table->string('sms_contact', 50)->after('name')->nullable();
            $table->string('email_contact', 120)->after('name')->nullable();
            $table->string('whatsapp_contact', 50)->after('name')->nullable();
            $table->longText('attributes')->nullable()->after('group_id')->comment("Contact Informations");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('uid');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('sms_contact');
            $table->dropColumn('email_contact');
            $table->dropColumn('whatsapp_contact');
            $table->dropColumn('attributes');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("groups", function (Blueprint $table) {
            $table->string("uid", 32)->index()->after("id")->nullable();
            $table->longText('contact_attributes')->nullable()->after('name')->comment("Common Attributes Found in Contacts under this Group");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('uid');
        });
    }
}

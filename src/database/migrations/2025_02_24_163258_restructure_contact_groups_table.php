<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RestructureContactGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::rename('groups', 'contact_groups');
        DB::statement("ALTER TABLE `contact_groups` CHANGE `status` `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'");

        DB::table('contact_groups')->where('status', '0')->update(['status' => 'inactive']);
        DB::table('contact_groups')->where('status', '1')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::table('contact_groups')->where('status', 'inactive')->update(['status' => '0']);
        DB::table('contact_groups')->where('status', 'active')->update(['status' => '1']);
        DB::statement("ALTER TABLE `contact_groups` CHANGE `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1'");
        Schema::rename('contact_groups', 'groups');
    }
}
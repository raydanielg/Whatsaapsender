<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class UpdateStatusColumnInGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `groups` CHANGE `status` `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `groups` CHANGE `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1'");
    }
}

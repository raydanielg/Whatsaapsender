<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE `contacts`
            CHANGE COLUMN `attributes` `meta_data` LONGTEXT,
            CHANGE COLUMN `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'Active: 1, Inactive: 0'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}

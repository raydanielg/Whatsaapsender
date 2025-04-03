<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyPricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pricing_plans`
            CHANGE COLUMN `recommended_status` `recommended_status` ENUM('0', '1') NOT NULL DEFAULT '0' COMMENT 'Active: 1, Inactive: 0',
            CHANGE COLUMN `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'Active: 1, Inactive: 0'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

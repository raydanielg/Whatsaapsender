<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `payment_methods`
            CHANGE COLUMN `currency_id` `currency_code` VARCHAR(32) NOT NULL,
            CHANGE COLUMN `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'Active: 1, Banned: 0'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add your rollback logic here
    }
}
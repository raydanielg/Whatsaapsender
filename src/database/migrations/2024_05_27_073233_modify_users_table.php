<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` 
            CHANGE COLUMN `credit` `sms_credit` INT NOT NULL DEFAULT '0' COMMENT 'Allocated by current Plan',
            CHANGE COLUMN `email_credit` `email_credit` INT NOT NULL DEFAULT '0' COMMENT 'Allocated by current Plan',
            CHANGE COLUMN `whatsapp_credit` `whatsapp_credit` VARCHAR(299) NOT NULL DEFAULT '0' COMMENT 'Allocated by current Plan',
            CHANGE COLUMN `sms_gateway` `api_sms_method` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'SMS API Gateway: 0, Android Gateway: 1',
            CHANGE COLUMN `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'Active: 1, Banned: 0',
            CHANGE COLUMN `email_verified_status` `email_verified_status` ENUM('0', '1') NOT NULL DEFAULT '0' COMMENT 'YES: 1, NO: 0',
            CHANGE COLUMN `contact_attributes` `contact_meta_data` TEXT,
            MODIFY COLUMN `uid` VARCHAR(32) NOT NULL,
            MODIFY COLUMN `name` VARCHAR(255) NOT NULL,
            MODIFY COLUMN `email` VARCHAR(70) NOT NULL,
            MODIFY COLUMN `google_id` VARCHAR(255),
            MODIFY COLUMN `address` TEXT,
            MODIFY COLUMN `image` VARCHAR(191),
            MODIFY COLUMN `password` VARCHAR(255),
            MODIFY COLUMN `api_key` VARCHAR(255),
            MODIFY COLUMN `email_verified_send_at` TIMESTAMP,
            MODIFY COLUMN `created_at` TIMESTAMP,
            MODIFY COLUMN `updated_at` TIMESTAMP
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` 
            CHANGE COLUMN `sms_credit` `credit` INT NOT NULL DEFAULT '0' COMMENT 'Allocated by current Plan',
            CHANGE COLUMN `email_credit` `email_credit` INT NOT NULL DEFAULT '0' COMMENT 'Allocated by current Plan',
            CHANGE COLUMN `whatsapp_credit` `whatsapp_credit` VARCHAR(299) NOT NULL DEFAULT '0' COMMENT 'Allocated by current Plan',
            CHANGE COLUMN `api_sms_method` `sms_gateway` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'SMS API Gateway: 0, Android Gateway: 1',
            CHANGE COLUMN `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1' COMMENT 'Active: 1, Banned: 0',
            CHANGE COLUMN `email_verified_status` `email_verified_status` ENUM('0', '1') NOT NULL DEFAULT '0' COMMENT 'YES: 1, NO: 0',
            CHANGE COLUMN `contact_meta_data` `contact_attributes` TEXT,
            MODIFY COLUMN `uid` VARCHAR(32) NOT NULL,
            MODIFY COLUMN `name` VARCHAR(255) NOT NULL,
            MODIFY COLUMN `email` VARCHAR(70) NOT NULL,
            MODIFY COLUMN `google_id` VARCHAR(255),
            MODIFY COLUMN `address` TEXT,
            MODIFY COLUMN `image` VARCHAR(191),
            MODIFY COLUMN `password` VARCHAR(255),
            MODIFY COLUMN `api_key` VARCHAR(255),
            MODIFY COLUMN `email_verified_send_at` TIMESTAMP,
            MODIFY COLUMN `created_at` TIMESTAMP,
            MODIFY COLUMN `updated_at` TIMESTAMP
        ");
    }
}

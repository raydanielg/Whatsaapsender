<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify the `templates` table by adding and modifying the necessary columns
        DB::statement('
            ALTER TABLE `templates`
            ADD COLUMN `uid` CHAR(32) AFTER `id`,
            ADD COLUMN `type` ENUM(\'1\', \'2\', \'3\') COMMENT \'SMS: 1, WhatsApp: 2, Email: 3\' AFTER `user_id`,
            ADD COLUMN `predefined` ENUM(\'0\', \'1\') COMMENT \'Yes: 1, No: 0\' AFTER `type`,
            ADD COLUMN `plugin` ENUM(\'0\', \'1\') COMMENT \'Yes: 1, No: 0\' AFTER `predefined`,
            ADD COLUMN `cloud_id` BIGINT UNSIGNED NULL AFTER `type`,
            ADD COLUMN `slug` VARCHAR(255) AFTER `name`,
            ADD COLUMN `template_data` JSON AFTER `slug`,
            ADD COLUMN `provider` TINYINT NULL AFTER `template_data`,
            ADD COLUMN `meta` TEXT NULL AFTER `provider`,
            MODIFY `name` VARCHAR(255) NULL AFTER `cloud_id`,
            MODIFY `status` ENUM(\'0\', \'1\') COMMENT \'Inactive: 0, Active: 1\' AFTER `meta`,
            MODIFY `created_at` TIMESTAMP NULL DEFAULT NULL AFTER `status`,
            MODIFY `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`,
            DROP COLUMN `message`
        ');

        // Ensure the `id` column is the primary key and auto-incrementing
        DB::statement('ALTER TABLE `templates` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverse the changes made in the up() method
        DB::statement('
            ALTER TABLE `templates`
            DROP COLUMN `uid`,
            DROP COLUMN `type`,
            DROP COLUMN `plugin`,
            DROP COLUMN `cloud_id`,
            DROP COLUMN `slug`,
            DROP COLUMN `template_data`,
            DROP COLUMN `provider`,
            DROP COLUMN `meta`,
            MODIFY `name` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY `status` ENUM(\'0\', \'1\') COMMENT \'Inactive: 0, Active: 1\',
            MODIFY `created_at` TIMESTAMP NULL DEFAULT NULL,
            MODIFY `updated_at` TIMESTAMP NULL DEFAULT NULL
        ');
    }
}

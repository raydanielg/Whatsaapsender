<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusColumnInPricingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `pricing_plans` CHANGE `status` `status` ENUM('active', 'inactive', '1', '0') NOT NULL DEFAULT 'active'");
        DB::table('pricing_plans')->where('status', '0')->update(['status' => 'inactive']);
        DB::table('pricing_plans')->where('status', '1')->update(['status' => 'active']);
        DB::statement("ALTER TABLE `pricing_plans` CHANGE `status` `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `pricing_plans` CHANGE `status` `status` ENUM('active', 'inactive', '1', '0') NOT NULL DEFAULT 'active'");
        DB::table('pricing_plans')->where('status', 'inactive')->update(['status' => '0']);
        DB::table('pricing_plans')->where('status', 'active')->update(['status' => '1']);
        DB::statement("ALTER TABLE `pricing_plans` CHANGE `status` `status` ENUM('0', '1') NOT NULL DEFAULT '1'");
    }
}

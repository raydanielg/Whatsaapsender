<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ImportSqlFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $path = storage_path('app');

        // Read and execute the templates.sql file
        $templatesSqlPath = $path . '/templates.sql';
        if (File::exists($templatesSqlPath)) {
            $templatesSql = File::get($templatesSqlPath);
            DB::unprepared($templatesSql);
            File::delete($templatesSqlPath);
        }

        // Read and execute the settings.sql file
        $settingsSqlPath = $path . '/settings.sql';
        if (File::exists($settingsSqlPath)) {
            $settingsSql = File::get($settingsSqlPath);
            DB::unprepared($settingsSql);
            File::delete($settingsSqlPath);
        }

        $frontendSqlPath = $path . '/frontend_sections.sql';
        if (File::exists($frontendSqlPath)) {
            $frontendSql = File::get($frontendSqlPath);
            DB::unprepared($frontendSql);
            File::delete($frontendSqlPath);
        }

        $blogSqlPath = $path . '/blogs.sql';
        if (File::exists($blogSqlPath)) {
            $blogSql = File::get($blogSqlPath);
            DB::unprepared($blogSql);
            File::delete($blogSqlPath);
        }

        $campaignSqlPath = $path . '/campaigns.sql';
        if (File::exists($campaignSqlPath)) {
            $campaignSql = File::get($campaignSqlPath);
            DB::unprepared($campaignSql);
            File::delete($campaignSqlPath);
        }

        $paymentMethodSqlPath = $path . '/payment_methods.sql';
        if (File::exists($paymentMethodSqlPath)) {
            $paymentMethodSql = File::get($paymentMethodSqlPath);
            DB::unprepared($paymentMethodSql);
            File::delete($paymentMethodSqlPath);
        }
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

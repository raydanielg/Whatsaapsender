<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecaptchaToGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->json('recaptcha')->nullable()->after('social_login');
            $table->enum('default_recaptcha', ["true", "false"])->nullable()->after('social_login');
            $table->enum('captcha_with_registration', ["true", "false"])->nullable()->after('social_login');
            $table->enum('captcha_with_login', ["true", "false"])->nullable()->after('social_login');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('recaptcha');
            $table->dropColumn('default_recaptcha');
            $table->dropColumn('captcha_with_registration');
            $table->dropColumn('captcha_with_login');
        });
    }
}

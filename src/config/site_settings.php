<?php

use Carbon\Carbon;
use App\Enums\StatusEnum;
use Predis\Response\Status;
use App\Enums\ContactAttributeEnum;

return [
    
    "email"                => "xsender@admin.com", 
    "phone"                => "###", 
    "plugin"               => StatusEnum::FALSE->status(),
    "captcha"              => StatusEnum::FALSE->status(),
    "address"              => "###", 
    "google_map_iframe"    => "###", 
    "site_name"            => "xsender", 
    "time_zone"            => "UTC",
    "app_version"          => "3.0.0", 
    "country_code"         => "1",
    "currency_name"        => "USD",
    "currency_symbol"      => "$",
    "webhook_verify_token" => "xsender",
    "api_sms_method"       => StatusEnum::TRUE->status(),
    "app_link"             => '###',
    
    "theme_dir"            => StatusEnum::FALSE->status(),
    "theme_mode"           => StatusEnum::TRUE->status(),

    // "login_with" => json_encode([
    //     'username',
    //     'email',
    //     'phone',
    // ]),

    "social_login"       => StatusEnum::FALSE->status(),
    "social_login_with"  => json_encode([
        'google_oauth' => [

            'status'        => StatusEnum::TRUE->status(),
            'client_id'     => '580301070453-job03fms4l7hrlnobt7nr5lbsk9bvoq9.apps.googleusercontent.com',
            'client_secret' => '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',
        ],
        // 'facebook_oauth' => [

        //     'status'        => StatusEnum::TRUE->status(),
        //     'client_id'     => '5604901016291309',
        //     'client_secret' => '41c62bf15c8189171196ffde1d2a6848',
        // ],
    ]),
    "available_plugins"   => json_encode([
        'beefree'   => [
            'status'        => StatusEnum::FALSE->status(),
            'client_id'     => 'b2369021-3e95-4ca4-a8c8-2ed3e2531865',
            'client_secret' => 'uL3UKV8V4RLv77vodnNTM8e93np9OYsS5P2mJ0373Nt9ghbwoRbn'
        ],
    ]),
    "member_authentication" => json_encode([
        'registration'   => StatusEnum::TRUE->status(),
        'login'          => StatusEnum::TRUE->status(),
        // 'login_with'     => [
        //     'username',
        //     'email',
        //     'phone',
        // ]
    ]),
    "google_recaptcha" => json_encode([

        'status'     => StatusEnum::FALSE->status(),
        'key'        => '6Lc5PpImAAAAABM-m4EgWw8vGEb7Tqq5bMOSI1Ot',
        'secret_key' => '6Lc5PpImAAAAACdUh5Hth8NXRluA04C-kt4Xdbw7',
    ]),

    "captcha_with_login"            => StatusEnum::FALSE->status(),
    "captcha_with_registration"     => StatusEnum::FALSE->status(),
    "registration_otp_verification" => StatusEnum::TRUE->status(),
    "email_otp_verification"        => StatusEnum::TRUE->status(),
    // "sms_otp_verification"          => StatusEnum::FALSE->status(),
    // "whatsapp_otp_verification"     => StatusEnum::FALSE->status(),
    "otp_expired_status"            => StatusEnum::FALSE->status(), //develop later
    // "sms_notifications"             => StatusEnum::FALSE->status(),
    "email_notifications"           => StatusEnum::TRUE->status(),
    // "whatsapp_notifications"        => StatusEnum::FALSE->status(),
    // "browser_notifications"         => StatusEnum::FALSE->status(),
    // "site_notifications"            => StatusEnum::FALSE->status(),

    // "default_sms_template"      => "hi {{name}}, {{message}}",
    "default_email_template"    => "hi, {{message}}",
    // "default_whatsapp_template" => "hi {{name}}, {{message}}",

    // "sms_delivery_method"       => StatusEnum::TRUE->status(),

    "contact_meta_data" => json_encode([
        "date_of_birth" => [
            "status" => StatusEnum::TRUE->status(),
            "type"   => ContactAttributeEnum::DATE->value
        ]
    ]),

    "last_cron_run"            => Carbon::now(),
    // "cron_pop_up"              => StatusEnum::FALSE->status(),
    "onboarding_bonus"         => StatusEnum::FALSE->status(),
    "onboarding_bonus_plan"    => null,
    "debug_mode"               => StatusEnum::FALSE->status(),
    "maintenance_mode"         => StatusEnum::FALSE->status(),
    "maintenance_mode_message" => "Please be advised that there will be scheduled downtime across our network from 12.00AM to 2.00AM",
    "landing_page"             => StatusEnum::TRUE->status(),

    "whatsapp_word_count"    => "320",
    "sms_word_count"         => "320",
    "sms_word_unicode_count" => "320",

    "primary_color"          => "#f25d6d",
    "secondary_color"        => "#f64b4d",
    "trinary_color"          => "#ffa360",
    "primary_text_color"     => "#ffffff",

    "copyright"              => "iGen Solutions Ltd",
    
    "mime_types" => json_encode([
        'png', 
        'jpg', 
        'jpeg'
    ]),
    "max_file_size"   => 20000,
    "max_file_upload" => 4,
    // "storage_unit"    => "KB",
    // 'storage'         => "local",
    // "store_as_webp"   => StatusEnum::FALSE->status(),

    "currencies" => json_encode([

        "USD" => [
            "name"   => "United States Dollar",
            "symbol" => "$",
            "rate"   => "1",
            "status" => StatusEnum::TRUE->status(),
            "is_default" => StatusEnum::TRUE->status()
        ],
        "BDT" => [
            "name"   => "Bangladeshi Taka",
            "symbol" => "৳",
            "rate"   => "114",
            "status" => StatusEnum::FALSE->status(),
            "is_default" => StatusEnum::FALSE->status()
        ],
    ]),

    "paginate_number" => 7,

    "auth_heading" => "Start turning your ideas into reality.",
    "authentication_background" => "###",
    "authentication_background_inner_image_one" => "###",
    "authentication_background_inner_image_two" => "###",
    "meta_title" => "Welcome To Xsender",
    "meta_description" => "Start your marketing journey today",
    "meta_keywords" => json_encode([
        "bulk",
        "sms",
        "email",
        "whatsapp",
        "marketing"
    ]),
];

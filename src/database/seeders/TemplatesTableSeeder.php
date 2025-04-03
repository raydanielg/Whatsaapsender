<?php

namespace Database\Seeders;

use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Enums\TemplateProvider;
use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates = [
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Global Template',
                'slug' => 'GLOBAL_TEMPLATE',
                'template_data' => site_settings('default_email_template'),
                'meta_data' => json_encode(['name' => 'username', 'message' => 'Email Data']),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::TRUE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => null,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Password Reset',
                'slug' => 'PASSWORD_RESET',
                'template_data' => ['subject' => 'Password Reset', 'mail_body' => '<p>We received a request to reset your account password at {{code}} and request time {{time}}<br></p>'],
                'meta_data' => json_encode([
                    'code' => 'Password Reset Code', 
                    'time' => 'Time']),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Admin Password Reset',
                'slug' => 'ADMIN_PASSWORD_RESET',
                'template_data' => ['subject' => 'Admin Password Reset', 'mail_body' => '<p>We received a request to reset your account password at {{code}} and request time {{time}}<br></p>'],
                'meta_data' => json_encode([
                    'code' => 'Password Reset Code', 
                    'time' => 'Time'
                ]),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Payment Confirmation',
                'slug' => 'PAYMENT_CONFIRMED',
                'template_data' => ['subject' => 'Payment Confirmation', 'mail_body' => '<p>Hello there,&nbsp;<br>The payment request made on <b>{{name}} </b>on <b>{{time}}</b>&nbsp;has been received.&nbsp;<br></p><p><br>Transaction Number: <b>{{trx}}</b><br>Payment Method: <b>{{method_name}}</b><br>Payment Method Currency: <b>{{method_currency}}</b><br><br>Payment Gateway Charge: <b>{{charge}}</b><br>Payment Amount: <b>{{amount}}</b><br><br>Thank you for trusting our services. We hope to deliver the best experience for you</p>'],
                'meta_data' => json_encode([
                    'trx'    => 'Transaction Number', 
                    'amount' => 'Payment Amount',
                    'charge' => 'Payment Gateway Charge',
                    'currency' => 'Site Currency',
                    'rate' => 'Conversion Rate',
                    'method_name' => 'Payment Method name'
                ]),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Password Reset Confirm',
                'slug' => 'PASSWORD_RESET_CONFIRM',
                'template_data' => ['subject' => 'Password reset confirmation', 'mail_body' => '<p>Your password has been updated on {{name}} at {{time}}<br></p>'],
                'meta_data' => json_encode([
                    'name' => 'Site Name', 
                    'time' => 'Time']),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Registration Verify',
                'slug' => 'REGISTRATION_VERIFY',
                'template_data' => ['subject' => 'Registration Verify', 'mail_body' => '<p><span style="background-color: var(--card-bg);">Hello, We received a request to create an account on {{name}}. You need to verify the email first, your verification code is {{code}} and the request time is {{time}}</span><br></p>'],
                'meta_data' => json_encode([
                    'name' => 'Site Name', 
                    'code' => 'Verification Code', 
                    'time' => 'Time']),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Test Mail',
                'slug' => 'TEST_MAIL',
                'template_data' => ['subject' => 'Test Mail', 'mail_body' => '<h5>Hello, {{name}}</h5><h5>This is testing the mail-to-mail configuration.</h5><h5>Request time {{time}}</h5>'],
                'meta_data' => json_encode([
                    'name' => 'Site Name', 
                    'time' => 'Time'
                ]),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Insuficient Credits',
                'slug' => 'INSUFFICIENT_CREDIT',
                'template_data' => ['subject' => 'Insuficient Credits', 'mail_body' => '<p>Hello there,<br>Unfortunately, You have run out of credits on {{name}} for {{type}}. You need to purchase a new subscription plan. Credits ran out at {{time}}</p>'],
                'meta_data' => json_encode([
                    'type' => 'Service Type (sms/email/whatsapp)',
                    'name' => 'Site Name', 
                    'time' => 'Time']),
                'plugin' =>  StatusEnum::FALSE->status(),
                'default' =>  StatusEnum::TRUE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' => TemplateProvider::CK_EDITOR->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Template One',
                'slug' => 'Template_ONE',
                'template_data' => [],
                'meta_data' => null,
                'plugin' =>  StatusEnum::TRUE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' =>  TemplateProvider::BEE_FREE->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Template Two',
                'slug' => 'Template_TWO',
                'template_data' => [],
                'meta_data' => null,
                'plugin' =>  StatusEnum::TRUE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' =>  TemplateProvider::BEE_FREE->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Template Three',
                'slug' => 'Template_THREE',
                'template_data' => [],
                'meta_data' => null,
                'plugin' =>  StatusEnum::TRUE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' =>  TemplateProvider::BEE_FREE->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Template Four',
                'slug' => 'Template_FOUR',
                'template_data' => [],
                'meta_data' => null,
                'plugin' =>  StatusEnum::TRUE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' =>  TemplateProvider::BEE_FREE->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Template Five',
                'slug' => 'Template_FIVE',
                'template_data' => [],
                'meta_data' => null,
                'plugin' =>  StatusEnum::TRUE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' =>  TemplateProvider::BEE_FREE->value,
            ],
            [
                'type' =>  ServiceType::EMAIL->value,
                'name' => 'Template Six',
                'slug' => 'Template_SIX',
                'template_data' => [],
                'meta_data' => null,
                'plugin' =>  StatusEnum::TRUE->status(),
                'default' =>  StatusEnum::FALSE->status(),
                'global' =>  StatusEnum::FALSE->status(),
                'status' =>  StatusEnum::TRUE->status(),
                'user_id' => null,
                'cloud_id' => null,
                'provider' =>  TemplateProvider::BEE_FREE->value,
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}

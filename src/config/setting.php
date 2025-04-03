<?php

return [
    "google" => [
        'g_client_id'     => "####",
        'g_client_secret' => "###",
        'g_client_status' => "#####",
    ],

    "gateway_credentials" => [
        "sms" => [
            "default_gateway_id" => 1,
            
            "101NEXMO" => [
                "api_key"    => "####",
                "api_secret" => "####",
                "sender_id"  => 1
            ],
            "102TWILIO" => [
                "account_sid" => "####",
                "auth_token"  => "####",
                "from_number" => "####",
                "sender_id"   => "####"
            ],
            "103MESSAGE_BIRD" => [
                "access_key" => "####",
                "sender_id"  => "####",
            ],
            "104TEXT_MAGIC" => [
                "api_key"             => "####",
                "text_magic_username" => "#####",
                "sender_id"           => "####"
            ],
            "105CLICKA_TELL" => [
                "clickatell_api_key" => "####",
                "sender_id"          => "####"
            ],
            "106INFOBIP" => [
                "infobip_base_url" => "####",
                "infobip_api_key"  => "####",
                "sender_id"        => "####"
            ],
            "107SMS_BROADCAST" => [
                "sms_broadcast_username" => "####",
                "sms_broadcast_password" => "####",
            ],
            "108MIM_SMS"       => [
                "api_url"   => "###",
                "api_key"   => "###",
                "sender_id" => "###"
            ],
            "109AJURA_SMS" => [

                "api_url"    => "###",
                "api_key"    => "###",
                "secret_key" => "###",
                "sender_id"  => "###"
            ],
            "110MSG91" => [
                "api_url"   => "###",
                "auth_key"  => "###",
                "flow_id"   => "###",
                "sender_id" => "###"
            ]
        ],
   
        "email" => [
            'smtp' => [
				'host'       => 'smtp.mailtrap.io',
				'driver'     => 'SMTP',
				'port'       => '2525',
				'encryption' => [

                   'Standard encryption (TLS)' => 'tls',
                   'Secure encryption (SSL)'   => 'ssl',
                   'PowerMTA Server'           => 'pwmta',
                   'STARTTLS'                  => 'starttls',
                   'None or No SSL'            => 'none',
                ],
				'username'   => 'Username',
				'password'   => 'Password',
                ]
			,
			'sendgrid'=>[
				'secret_key' => 'Api Secret Key',
			]
			,
			'aws' =>[
				'profile'      => 'Ses Profile',
				'version'      => 'Ses Version',
				'region'       => 'Ses Region',
				'sender_email' => 'Ses Sender Email ',
			]
			,
			'mailjet' => [
                'secret_key' => 'Api Secret Key',
                'api_key' => 'Api Public Key'
            ]
			,
			'mailgun' => [
				'secret_key'      => 'Api Secret Key',
				'verified_domain' => 'Verified Domain'
			],
        ]
    ],

    "recaptcha" => [
        'recaptcha_key'    => "####",
        'recaptcha_secret' => "###",
        'recaptcha_status' => "2",
    ],
    
    "webhook" => [
        'callback_url' => "####",
        'verify_token' => "###",
    ],

    "whatsapp_business_credentials" => [

        'default' => [

            'version'           => "v19.0",
        ],
        'required' => [

            'user_access_token'            => "###",
            'phone_number_id'              => "###",
            'whatsapp_business_account_id' => "###",
        ],
        'optional' => [

            'business_id'       => "###",
        ]
        
        ],

    'file_types'=> ['3dmf',    '3dm',    'avi',    'ai',    'bin',    'bin',    'bmp',    'cab',    'c',    'c++',    'class',    'css',    'csv',    'cdr',    'doc',    'dot',    'docx',    'dwg',    'eps',    'exe',    'gif',    'gz',    'gtar',    'flv',    'fh4',    'fh5',    'fhc',    'help',    'hlp',    'html',    'htm',    'ico',    'imap',    'inf',    'jpe',    'jpeg',    'jpg',    'js',    'java',    'latex',    'log',    'm3u',    'midi',    'mid',    'mov',    'mp3',    'mpeg',    'mpg',    'mp2',    'ogg',    'phtml',    'php',    'pdf',    'pgp',    'png',    'pps',    'ppt',    'ppz',    'pot',    'ps',    'qt',    'qd3d',    'qd3',    'qxd',    'rar',    'ra',    'ram',    'rm',    'rtf',    'spr',    'sprite',    'stream',    'swf',    'svg',    'sgml',    'sgm',    'tar',    'tiff',    'tif',    'tgz',    'tex',    'txt',    'vob',    'wav',    'wrl',    'wrl',    'xla',    'xls',    'xls',    'xlc',    'xml',    'xlsx',    'zip',   'webp'],

    "login_attribute" => [
        'username',
        'phone',
        'email'
    ],

    "logo_keys" => [
        "site_logo",
        "site_square_logo",
        "panel_logo",
        "panel_square_logo",
        "favicon",
        // "loader_icon",
        "meta_image",
    ],

    "auth_image_keys" => [
        "authentication_background",
        "authentication_background_inner_image_one",
        "authentication_background_inner_image_two",
    ],

    "file_path" =>  [
        
        'authentication_background' => [
            'path' => 'assets/file/images/global/auth/main',
            'size' => '960x865',
        ],
        'authentication_background_inner_image_one' => [
            'path' => 'assets/file/images/global/auth/child/one',
            'size' => '494x300',
        ],
        'authentication_background_inner_image_two' => [
            'path' => 'assets/file/images/global/auth/child/two',
            'size' => '494x300',
        ],
        'admin_profile' => [
            'path' => 'assets/file/images/backend/admin_profile',
            'size' => '350x350',
        ],
        
        'automatic_payment' => [
            'path' => 'assets/file/images/global/automatic_payment',
            'size' => '350x350',
        ],
        'manual_payment' => [
            'path' => 'assets/file/images/global/manual_payment',
            'size' => '350x350',
        ],

        'site_logo' => [
            'path' => 'assets/file/images/backend/site_logo',
            'size' => '438x117',
        ],
        'site_square_logo' => [
            'path' => 'assets/file/images/backend/site_square_logo',
            'size' => '160x160',
        ],
        'panel_logo' => [
            'path' => 'assets/file/images/global/panel_logo',
            'size' => '350x75',
        ],
        'panel_square_logo' => [
            'path' => 'assets/file/images/global/panel_square_logo',
            'size' => '160x160',
        ],
        'favicon' => [
            'path' => 'assets/file/images/global/favicon',
            'size' => '16x16',
        ],
        'meta_image' => [
            'path' => 'assets/file/images/global/meta_image',
            'size' => '350x75',
        ],
        'blog_images' => [
            'path' => 'assets/file/images/global/blog_images',
            'size' => '1165x777',
        ],
        'social_login' => [
            'google' => [
                'path' => 'assets/file/images/global/social_login',
                'size' => '512x512',
            ]
            ],
        'frontend' => [
            'banner_image' => [
                'path' => 'assets/file/images/frontend/banner_image',
                'size' => '1616x998',
            ],
            'banner_second_image' => [
                'path' => 'assets/file/images/frontend/banner_second_image',
                'size' => '1200x490',
            ],
            'video_button_image' => [
                'path' => 'assets/file/images/frontend/video_button_image',
                'size' => '600x250',
            ],
            'payment_gateway_image' => [
                'path' => 'assets/file/images/frontend/payment_gateway_image',
                'size' => '608x60',
            ],
            'unsubscription_image' => [
                'path' => 'assets/file/images/frontend/unsubscription_image',
                'size' => '200x200',
            ],
            'sms_service_image' => [
                'path' => 'assets/file/images/frontend/sms_service_image',
                'size' => '1136x866',
            ],
            'whatsapp_service_image' => [
                'path' => 'assets/file/images/frontend/whatsapp_service_image',
                'size' => '1136x866',
            ],
            'email_service_image' => [
                'path' => 'assets/file/images/frontend/email_service_image',
                'size' => '1136x866',
            ],
            'plan_breadcrumb_image' => [
                'path' => 'assets/file/images/frontend/plan_breadcrumb_image',
                'size' => '1920x1080',
            ],
            'about_breadcrumb_image' => [
                'path' => 'assets/file/images/frontend/about_breadcrumb_image',
                'size' => '1920x1080',
            ],
            'about_overview_image' => [
                'path' => 'assets/file/images/frontend/about_overview_image',
                'size' => '1410x964',
            ],
            'contact_breadcrumb_image' => [
                'path' => 'assets/file/images/frontend/contact_breadcrumb_image',
                'size' => '1920x1080',
            ],
            'blog_breadcrumb_image' => [
                'path' => 'assets/file/images/frontend/contact_breadcrumb_image',
                'size' => '1920x1080',
            ],
            'service_breadcrumb_image' => [
                'path' => 'assets/file/images/frontend/service_breadcrumb_image',
                'size' => '1920x1080',
            ],
            'policy_breadcrumb_image' => [
                'path' => 'assets/file/images/frontend/policy_breadcrumb_image',
                'size' => '1920x1080',
            ],
            'service_overview_image' => [
                'path' => 'assets/file/images/frontend/service_overview_image',
                'size' => '700x525',
            ],
            'service_details_image' => [
                'path' => 'assets/file/images/frontend/service_details_image',
                'size' => '1010x690',
            ],
            'service_highlight_image' => [
                'path' => 'assets/file/images/frontend/service_highlight_image',
                'size' => '1410x964',
            ],
            
            'element_content' => [
                'banner' => [
                    'company_logo' => [
                        'path' => 'assets/file/images/frontend/element_content/banner/company_logo',
                        'size' => '325x110',
                    ]   
                    ],
                'feature' => [
                    'feature_image' => [
                        'path' => 'assets/file/images/frontend/element_content/feature/feature_image',
                        'size' => '978x1462',
                    ]   
                ],
                'workflow' => [
                    'process_image' => [
                        'path' => 'assets/file/images/frontend/element_content/workflow/process_image',
                        'size' => '1200x675',
                    ]   
                ],
                'feedback' => [
                    'reviewer_image' => [
                        'path' => 'assets/file/images/frontend/element_content/feedback/reviewer_image',
                        'size' => '740x740',
                    ]   
                ],
                'gateway' => [
                    'gateway_image' => [
                        'path' => 'assets/file/images/frontend/element_content/gateway/gateway_image',
                        'size' => '400x70',
                    ]   
                ],
                'service_feature' => [
                    'service_feature_image' => [
                        'path' => 'assets/file/images/frontend/element_content/service/service_feature_image',
                        'size' => '240x240',
                    ],
                ],
                'connect_section' => [
                    'conenct_image' => [
                        'path' => 'assets/file/images/frontend/element_content/connect_section/conenct_image',
                        'size' => '200x200',
                    ]   
                ],
            ]
        ],

    ],

    "json_object" => [
        'currencies',
        'social_login_with',
        'available_plugins',
        'member_authentication',
        'google_recaptcha',
        'contact_meta_data',
        'mime_types',
        'meta_keywords',
        'accessible_sms_api_gateways',
        'accessible_sms_android_gateways',
        'verify_email_additional_checks',
        'disposable_domain_list',
        'email_role_list',
        'tld_list',
        'common_domain'
    ]
];


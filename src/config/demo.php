<?php

use App\Enums\SettingKey;

## INSTRUCTIONS ##

/**
 * The global message will be the default fallback for all demo mode messaging.
 * Each feature can have its own 'enabled' toggle and a 'default' message that applies when no restriction-specific message is defined.
 * Restriction-specific messages are tied to individual restrictions (e.g., 'max_records', 'block_store') and take priority when present.
 * If a restriction-specific message is not available, the feature's 'default' message is used.
 * If the feature's 'default' message is not available, the global message is used.
 * Restrictions can use values like SettingKey::ALL->value ("all") or specific numbers for flexibility.
 */

return [
    'enabled' => env('DEMO_MODE', false),
    'messages' => [
        'global' => 'This is a demo environment. Some actions may be restricted.',
    ],
    'features' => [
     
          'settings' => [
               'enabled' => true,
               'messages' => [
                    'default' => 'Theme is in demo mode.',
                    'restrictions' => [
                         'block_theme_update'     => '',
                         'block_ltr_rtl_update'   => '',
                         'block_color_update'     => '',
                         'block_site_name_update' => '',
                    ],
               ],
               'restrictions' => [
               ],
          ],
          'users' => [
            'enabled' => true,
            'messages' => [
                'default' => 'User management is disabled within demo mode.',
                'restrictions' => [
                    'max_records'       => 'User records are unlimited or limited in demo mode.',
                    'store'             => 'Creating new users is disabled in demo mode.',
                    'update'            => 'Updating users is disabled in demo mode.',
                    'delete'            => 'Deleting users is disabled in demo mode.',
                    'login_as_user'     => 'Logging in as a user from Admin panel is disabled in demo mode.',
                    'filter'            => 'Filtering users is disabled in demo mode.',
                ],
            ],
            'restrictions' => [
                'max_records'      => SettingKey::ALL->value, 
                'store'            => true,
                'update'           => true,
                'delete'           => true,
                'login_as_user'    => true,
                'filter'           => true,
            ],
        ],
    ],
];
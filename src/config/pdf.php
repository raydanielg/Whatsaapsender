<?php
return
    [
        'mode'                  => 'utf-8',
        'format'                => 'A4',
        'author'                => '',
        'subject'               => '',
        'keywords'              => '',
        'creator'               => 'Laravel Pdf',
        'display_mode'          => 'fullpage',
        'tempDir'               => base_path('temp/'),
        'font_path' => base_path('assets/fonts/'),
        'font_data' => [
            'roboto' => [
                'R'  => 'Roboto-Regular.ttf',   
                'B'  => 'Roboto-Bold.ttf', 
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
            'hindsiliguri' => [
                'R'  => 'HindSiliguri-Regular.ttf',
                'B'  => 'HindSiliguri-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
            'arnamu' => [
                'R'  => 'arnamu.ttf', 
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ]
        ]
    ];
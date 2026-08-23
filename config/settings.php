<?php

return [

    'KT_THEME_BOOTSTRAP' => [
        'default' => \App\Core\Bootstrap\BootstrapDefault::class,
        'auth' => \App\Core\Bootstrap\BootstrapAuth::class,
        'system' => \App\Core\Bootstrap\BootstrapSystem::class,
    ],

    'KT_THEME' => 'metronic',

    # Theme layout templates directory

    'KT_THEME_LAYOUT_DIR' => 'layout',


    # Theme Mode
    # Value: light | dark | system

    'KT_THEME_MODE_DEFAULT' => 'light',
    'KT_THEME_MODE_SWITCH_ENABLED' => true,


    # Theme Direction
    # Value: ltr | rtl

    'KT_THEME_DIRECTION' => 'ltr',


    # Keenicons
    # Value: duotone | outline | bold

    'KT_THEME_ICONS' => 'duotone',


    # Theme Assets

    'KT_THEME_ASSETS' => [
        
        'fonts' => [
            'https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700',
        ],
        'global' => [
            'css' => [
                'assets/plugins/global/plugins.bundle.css',
                'assets/css/style.bundle.css',
            ],
            'js' => [
                'assets/plugins/global/plugins.bundle.js',
                'assets/js/scripts.bundle.js',
                'assets/js/widgets.bundle.js',
            ],
        ],
    ],


    # Theme Vendors

    'KT_THEME_VENDORS' => [
        // 'datatables' => [
        //     'css' => [
        //         'assets/plugins/custom/datatables/datatables.bundle.css',
        //     ],
        //     'js' => [
        //         'assets/plugins/custom/datatables/datatables.bundle.js',
        //     ],
        // ],
        
 
    ],

];

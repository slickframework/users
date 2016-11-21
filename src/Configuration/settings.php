<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// ------------------------------------
//  Default application settings
// ------------------------------------
$settings = [
    'environment' => 'production',
    'version' => 'v0.1.0',
    'server' => [
        'name' => 'http://localhost'
    ]
];

// ------------------------------------
//  Database access settings
// ------------------------------------
$settings['users-db'] = [
    'driver' => \Slick\Database\Adapter::DRIVER_MYSQL,
    'options' => [
        'host' => 'localhost',
        'database' => 'slick_users',
        'username' => 'root',
        'password' => '',
    ]
];

// ------------------------------------
//  Remember me settings
// ------------------------------------
$settings['rememberMe'] = [
    'expire' => 30*24*60*60, // Store for 30 days
    'cookie' => 'users-rmm'
];

// ------------------------------------
//  Sending e-mail settings
// ------------------------------------
$settings['email'] = [
    'from' => 'no-replay@slickframework.com',
    'subjects' => [
        'confirmation' => 'Slick Users - Account\'s e-mail confirmation',
        'recover' => 'Slick Users - Password recover instructions',
    ],
    'messages' => [
        'confirmation' => [
            'html' => 'email/confirmation.html.twig',
            'plain' => 'email/confirmation.plain.twig',
            'embed' => [
                'logo' => [
                    'image/png' => APP_PATH . '/webroot/img/users-icon.png'
                ]
            ]
        ],
        'recover' => [
            'html' => 'email/recover.html.twig',
            'plain' => 'email/recover.plain.twig',
            'embed' => [
                'logo' => [
                    'image/png' => APP_PATH . '/webroot/img/users-icon.png'
                ]
            ]
        ]
    ],
    'transport' => [
        'class' => \Slick\Mail\Transport\PhpMailTransport::class,
        /*
        'args' => [
            'options' => []
        ]
        */
    ]
];

// ------------------------------------
//  Logging settings
// ------------------------------------
$settings['logging'] = [
    'handler' => [
        'name' => 'null',
        'debug_level' => \Monolog\Logger::DEBUG
    ]
];

// ------------------------------------
//  Twig templates configuration
// ------------------------------------
$settings['template'] = [
    'path' => APP_PATH .'/templates'
];

// ------------------------------------
//  Load local environment settings
// ------------------------------------
if (is_file(__DIR__.'/settings.local.php')) {
    include __DIR__.'/settings.local.php';
}

return $settings;

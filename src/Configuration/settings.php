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
    'version' => 'v0.1.0'
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
//  Sending e-mail settings
// ------------------------------------
$settings['email'] = [
    'from' => 'no-replay@slickframework.com',
    'subjects' => [
        'confirmation' => 'Confirm your e-mail'
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
        ]
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

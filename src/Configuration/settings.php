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

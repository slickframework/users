<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Application bootstrap script
 * @var \Slick\Mvc\Application $application
 */

use Slick\Database\Adapter;
use Slick\Orm\Orm;
use Slick\Users\Bootstrap;

$config = \Slick\Configuration\Configuration::get('settings');

Bootstrap::initialise($application)->addRoutes();

$dbSettings = $config->get(
    'users-db',
    [
        'driver' => Adapter::DRIVER_SQLITE,
        'options' => ['file' => ':memory:']
    ]
);
$adapter = (new Adapter($dbSettings))->initialize();
Orm::getInstance()->setAdapter('slickUsers', $adapter);
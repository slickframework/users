<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @var Composer\Autoload\ClassLoader $autoLoader */
$autoLoader = include dirname(__DIR__).'/vendor/autoload.php';

session_start();

define('APP_PATH', dirname(__DIR__));

Phake::setClient(Phake::CLIENT_PHPUNIT);

$application = new \Slick\Mvc\Application();
$application->setConfigPath(APP_PATH.'/src/Configuration');

$bsFile = $application->getConfigPath().'/bootstrap.php';

if (is_file($bsFile)) {
    include $bsFile;
}

\Slick\Template\Template::addPath(APP_PATH.'/templates');
<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Gelf\Transport\UdpTransport;
use Gelf\MessageValidator;
use Slick\Configuration\Configuration;
use Slick\Di\Definition\ObjectDefinition;
use Slick\Http\PhpEnvironment\Request;
use Slick\Http\PhpEnvironment\Response;
use Slick\Http\Server;
use Slick\Mvc\Dispatcher;
use Slick\Mvc\Http\UrlRewrite;
use Slick\Mvc\Renderer;
use Slick\Mvc\Router;
use Slick\Mvc\Http\Session;
use Slick\Users\Service\Account\AccountEventEmitter;
use Slick\Users\Service\Account\Listener\AccountEventsProvider;
use Slick\Users\Service\Account\Register;

/**
 * @var $this \Slick\Mvc\Application
 */
$config = Configuration::get('settings');
$templatePath = $config->get('template.path');

/**
 * Default DI services definitions
 */
$services = [];

// ------------------------------------
// Application services
// ------------------------------------
$services['authentication'] = ObjectDefinition::create(\Slick\Users\Service\Authentication::class);
$services['accountEventsListenerProvider'] = ObjectDefinition::create(AccountEventsProvider::class);
$services['accountRegister'] = ObjectDefinition::create(Register::class)
    ->setConstructArgs(['@logger']);
$services['accountAuthentication'] = ObjectDefinition::create(\Slick\Users\Service\Account\Authentication::class)
    ->setConstructArgs(['@logger']);
$services['accountEventEmitter'] = ObjectDefinition::create(
    AccountEventEmitter::class
)->setMethod('useListenerProvider', ['@accountEventsListenerProvider']);

$services['gelfValidator'] = ObjectDefinition::create(MessageValidator::class);
$services['gelfTransport'] = ObjectDefinition::create(UdpTransport::class)
    ->setConstructArgs(
        [
            $config->get('logging.graylog.hostname', 'graylog'),
            $config->get('logging.graylog.port', 12201)
        ]
    )
;
$services['gelfPublisher'] = ObjectDefinition::create(\Gelf\Publisher::class)
    ->setConstructArgs(['@gelfTransport', '@gelfValidator']);

$services['defaultHandler'] = $config->get('logging.handler.name', 'gelf') == 'gelf'
    ? ObjectDefinition::create(\Monolog\Handler\GelfHandler::class)
        ->setConstructArgs(
            [
                '@gelfPublisher',
                $config->get('logging.handler.debug_level', 100)
            ]
        )
    : ObjectDefinition::create(\Slick\Common\Log\Handler\NullHandler::class);


$services['logger'] = function () {
    return \Slick\Users\Service\Logging::logger();
};

// ------------------------------------
// Default session driver
// ------------------------------------
$services['session'] = function() use ($config) {
    $options = $config->get(
        'session',
        [
            'driver' => \Slick\Http\Session::DRIVER_SERVER,
            'options' => []
        ]
    );
    $session = new \Slick\Http\Session($options);
    return $session->initialize();
};


// ------------------------------------
// HTTP request and response objects
// ------------------------------------
$services['request'] = ObjectDefinition::create(Request::class);
$services['response'] = ObjectDefinition::create(Response::class);


// ------------------------------------
// Middleware
// ------------------------------------
$services['session.middleware']     = ObjectDefinition::create(Session::class);
$services['authentication.middleware']     = ObjectDefinition::create(\Slick\Users\Service\Http\AuthenticationMiddleware::class);
$services['url.rewrite.middleware'] = ObjectDefinition::create(UrlRewrite::class);
$services['router.middleware']      = ObjectDefinition::create(Router::class)
    ->setMethod(
        'setRouteFile',
        [APP_PATH.'/src/Configuration/routes.yml']
    )
;
$services['dispatcher.middleware']  = ObjectDefinition::create(Dispatcher::class);
$services['renderer.middleware']    = ObjectDefinition::create(Renderer::class)
    ->setMethod(
        'addTemplatePath',
        [$templatePath]
    )
;


// ------------------------------------
// HTTP server middleware stack
// ------------------------------------
$services['middleware.runner'] = ObjectDefinition::create(Server::class)
    ->setConstructArgs(['@request', '@response'])
    ->setMethod('add', ['@session.middleware'])
    ->setMethod('add', ['@url.rewrite.middleware'])
    ->setMethod('add', ['@router.middleware'])
    ->setMethod('add', ['@authentication.middleware'])
    ->setMethod('add', ['@dispatcher.middleware'])
    ->setMethod('add', ['@renderer.middleware']);
return $services;
<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users;

use Aura\Router\Map;
use Aura\Router\RouterContainer;
use Slick\Mvc\Application;
use Slick\Mvc\Router;
use Slick\Orm\Event\Save;
use Slick\Orm\Orm;
use Slick\Template\Template;
use Slick\Users\Service\Domain\AuditEntitySaveListener;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;
use Slick\Users\Template\Extension\Authentication;

/**
 * Bootstrap slick/users package
 *
 * @package Slick\Users
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class Bootstrap
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var RouterContainer
     */
    private $routerContainer;

    /**
     * @var Orm
     */
    private $orm;

    private $listeners = [
        AuditEntitySaveListener::class => Save::ACTION_BEFORE_UPDATE
    ];

    /**
     * Needed to load the router container
     */
    use DependencyContainerAwareMethods;

    /**
     * Bootstrap
     *
     * @param Application $application
     */
    private function __construct(Application $application)
    {
        $this->application = $application;
        $application->getContainer(); // To initialize the main application container
        $this->registerListeners();
        Template::register(Authentication::class);
    }

    /**
     * Initialise Slick/Users
     *
     * @param Application $app
     *
     * @return Bootstrap
     */
    public static function initialise(Application $app)
    {
        return new Bootstrap($app);
    }

    public function addRoutes()
    {
        $map = $this->getRouterContainer()->getMap();
        $routes = $map->getRoutes();
        $map->setRoutes([]);
        $map->post('signUp', '/sign-up')
            ->defaults([
                'namespace' => 'Slick\Users\Controller',
                'controller' => 'accounts',
                'action' => 'sign-up',
            ])
            ->allows(['GET'])
        ;
        $map->post('signIn', '/sign-in')
            ->defaults([
                'namespace' => 'Slick\Users\Controller',
                'controller' => 'login',
                'action' => 'sign-in',
            ])
            ->allows(['GET'])
        ;
        $map->attach('user.', '/user', function(Map $map) {
            $map->tokens([
                'id'     => '\d+'
            ]);

            $map->get('browse', 's')
                ->defaults([
                    'namespace' => 'Slick\Users\Controller',
                    'controller' => 'users',
                    'action' => 'index',
                ]);
            $map->get('read', '/{id}')
                ->defaults([
                    'namespace' => 'Slick\Users\Controller',
                    'controller' => 'users',
                    'action' => 'show',
                ]);
            $map->post('edit', '/{id}/edit')
                ->allows(['PATCH', 'PUT', 'GET'])
                ->defaults([
                    'namespace' => 'Slick\Users\Controller',
                    'controller' => 'users',
                    'action' => 'edit',
                ]);
            $map->post('add', '')
                ->allows(['GET'])
                ->defaults([
                    'namespace' => 'Slick\Users\Controller',
                    'controller' => 'users',
                    'action' => 'add',
                ]);
            $map->post('delete', '/{id}/delete')
                ->allows(['GET', 'DELETE'])
                ->defaults([
                    'namespace' => 'Slick\Users\Controller',
                    'controller' => 'users',
                    'action' => 'delete',
                ]);



        });
        $routes = array_merge($map->getRoutes(), $routes);
        $map->setRoutes($routes);
    }

    /**
     * Gets routerContainer property
     *
     * @return RouterContainer
     */
    public function getRouterContainer()
    {
        if (!$this->routerContainer) {
            $this->setRouterContainer($this->loadRouterContainer());
        }
        return $this->routerContainer;
    }

    /**
     * Sets routerContainer property
     *
     * @param RouterContainer $routerContainer
     *
     * @return Bootstrap
     */
    public function setRouterContainer($routerContainer)
    {
        $this->routerContainer = $routerContainer;
        return $this;
    }

    /**
     * Gets ORM factory
     *
     * @return Orm
     */
    public function getOrm()
    {
        if (!$this->orm) {
            $this->setOrm(Orm::getInstance());
        }
        return $this->orm;
    }

    /**
     * Sets ORM factory
     *
     * @param Orm $orm
     *
     * @return Bootstrap
     */
    public function setOrm(Orm $orm)
    {
        $this->orm = $orm;
        return $this;
    }


    /**
     * Load router container from application router
     *
     * @return RouterContainer
     */
    private function loadRouterContainer()
    {
        /** @var Router $router */
        $router = $this->getContainer()->get('router.middleware');
        return $router->getRouterContainer();
    }

    /**
     * Adds the list of listeners to the ORM emitter
     */
    private function registerListeners()
    {
        foreach ($this->listeners as $class => $eventName) {
            $this->getOrm()
                ->getListenersProvider()
                ->addListener($eventName, new $class)
            ;
        }
    }
}

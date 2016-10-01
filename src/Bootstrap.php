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
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

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
        $application->getContainer();
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

}

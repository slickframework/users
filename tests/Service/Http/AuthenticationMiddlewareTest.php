<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Http;

use Aura\Router\Route;
use Slick\Http\PhpEnvironment\Request;
use Slick\Http\Response;
use Slick\Http\SessionDriverInterface;
use Slick\Http\Uri;
use Slick\Users\Service\Authentication;
use Slick\Users\Service\Http\AuthenticationMiddleware;
use Slick\Users\Tests\TestCase;

/**
 * Authentication Middleware Test
 *
 * @package Slick\Users\Tests\Service\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuthenticationMiddlewareTest extends TestCase
{
    /**
     * @var AuthenticationMiddleware
     */
    protected $middleware;

    /**
     * Set the SUT middleware object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->middleware = new AuthenticationMiddleware();
    }

    public function testNoAuthNeeded()
    {
        $route = new Route();
        $request = (new Request())
            ->withAttribute('route', $route);
        $response = new Response();
        $this->assertSame($response, $this->middleware->handle($request, $response));
    }

    public function testAuthenticatedAccess()
    {
        $service = \Phake::mock(Authentication::class);
        \Phake::when($service)->isGuest()->thenReturn(false);
        $this->middleware->setAuthenticationService($service);
        $route = new Route();
        $route->auth(true);
        $request = (new Request())
            ->withAttribute('route', $route);
        $response = new Response();
        $this->assertSame($response, $this->middleware->handle($request, $response));
    }

    public function testProtectedAccess()
    {
        $session = \Phake::mock(SessionDriverInterface::class);
        $service = \Phake::mock(Authentication::class);
        \Phake::when($service)->isGuest()->thenReturn(true);
        $this->middleware->setAuthenticationService($service);
        $this->middleware->setSession($session);
        $route = new Route();
        $route->auth(true);
        $request = (new Request())
            ->withAttribute('route', $route)
            ->withUri(new Uri('http://0.0.0.0/profile'));
        $response = new Response();
        $response = $this->middleware->handle($request, $response);
        \Phake::verify($session)->set('redirectTo', (string) $request->getUri()->getPath());
        $this->assertEquals(
            $request->getBasePath().'sign-in',
            $response->getHeaderLine('location')
        );
        $this->assertEquals(302, $response->getStatusCode());
    }
}

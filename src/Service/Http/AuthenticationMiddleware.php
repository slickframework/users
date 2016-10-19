<?php

/**
 * This file is part of slick/users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Http;

use Aura\Router\Route;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\PhpEnvironment\Request;
use Slick\Http\Server\AbstractMiddleware;
use Slick\Http\Server\MiddlewareInterface;
use Slick\Users\Service\Authentication;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;
use Slick\Users\Shared\Http\Session\SessionAwareInterface;
use Slick\Users\Shared\Http\Session\SessionAwareMethods;

/**
 * Authentication Middleware
 *
 * @package Slick\Users\Service\Http
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class AuthenticationMiddleware extends AbstractMiddleware implements
    MiddlewareInterface,
    DependencyContainerAwareInterface,
    Authentication\AuthenticationAwareInterface,
    SessionAwareInterface
{
    const REDIRECT_KEY = 'redirectTo';

    /**
     * @var ServerRequestInterface|Request
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * To retrieve the authentication service
     */
    use Authentication\AuthenticationAwareMethods;

    /**
     * To use the dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * To use session
     */
    use SessionAwareMethods;

    /**
     * Handles a Request and updated the response
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function handle(
        ServerRequestInterface $request, ResponseInterface $response
    )
    {
        $this->request = $request;
        $this->response = $response;
        /** @var Route $route */
        $route = $request->getAttribute('route', null);
        if ($route && $this->checkRoute($route)) {
            return $this->response;
        }

        return $this->executeNext($request, $response);
    }

    /**
     * Check if route fails to authentication
     *
     * @param Route $route
     *
     * @return bool
     */
    protected function checkRoute(Route $route)
    {
        if (!$route->auth) {
            return false;
        }
        return $this->checkGuestAccess();
    }

    /**
     * Check if being a guest it need to be redirected to login page.
     *
     * @return bool
     */
    protected function checkGuestAccess()
    {
        $isGuest = $this->getAuthenticationService()->isGuest();
        if (!$isGuest) {
            return false;
        }
        $uri = (string) $this->request->getUri()->getPath();
        $this->getSession()->set(self::REDIRECT_KEY, $uri);
        $signInPage = $this->request->getBasePath().'sign-in';
        $this->response = $this->response
            ->withHeader('Location', $signInPage)
            ->withStatus(302)
        ;
        return true;
    }
}

<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server\AbstractMiddleware;
use Slick\Http\Server\MiddlewareInterface;

/**
 * Remember Me Login Middleware
 *
 * @package Slick\Users\Service\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RememberMeLoginMiddleware extends AbstractMiddleware implements MiddlewareInterface
{

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
        $cookie = $_COOKIE['users-rmm'];
        return $this->executeNext($request, $response);
    }
}

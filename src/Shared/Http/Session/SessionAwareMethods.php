<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Http\Session;

use Interop\Container\ContainerInterface;
use Slick\Http\SessionDriverInterface;

/**
 * Session Aware Methods
 *
 * @package Slick\Users\Shared\Http\Session
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait SessionAwareMethods
{
    /**
     * @var SessionDriverInterface
     */
    protected $session;

    /**
     * Get session driver object
     *
     * @return SessionDriverInterface
     */
    public function getSession()
    {
        if (!$this->session) {
            /** @var SessionDriverInterface $session */
            $session = $this->getContainer()->get('session');
            $this->setSession($session);
        }
        return $this->session;
    }

    /**
     * Set session driver object
     *
     * @param SessionDriverInterface $sessionDriver
     *
     * @return SessionAwareInterface|$this|self
     */
    public function setSession(SessionDriverInterface $sessionDriver)
    {
        $this->session = $sessionDriver;
        return $this;
    }

    /**
     * Get dependency container
     *
     * @return ContainerInterface
     */
    abstract public function getContainer();
}

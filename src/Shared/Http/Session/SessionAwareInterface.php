<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Http\Session;

use Slick\Http\SessionDriverInterface;

/**
 * Session Aware Interface
 *
 * @package Slick\Users\Shared\Http\Session
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface SessionAwareInterface
{

    /**
     * Get session driver object
     *
     * @return SessionDriverInterface
     */
    public function getSession();

    /**
     * Set session driver object
     *
     * @param SessionDriverInterface $sessionDriver
     *
     * @return SessionAwareInterface|$this|self
     */
    public function setSession(SessionDriverInterface $sessionDriver);
}

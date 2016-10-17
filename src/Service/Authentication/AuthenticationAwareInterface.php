<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Authentication;

use Slick\Users\Service\Authentication;

/**
 * AuthenticationAwareInterface
 *
 * @package Slick\Users\Service\Authentication
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface AuthenticationAwareInterface
{
    /**
     * Get authentication service
     *
     * @return Authentication\
     */
    public function getAuthenticationService();

    /**
     * Sets the authentication service
     *
     * @param Authentication $service
     *
     * @return $this|self|AuthenticationAwareInterface
     */
    public function setAuthenticationService(Authentication $service);
}

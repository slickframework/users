<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Authentication;

use Slick\Form\Element\ContainerInterface;
use Slick\Users\Service\Authentication;

/**
 * Authentication Aware Methods
 *
 * Implementation methods for AuthenticationAwareInterface
 *
 * @package Slick\Users\Service\Authentication
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait AuthenticationAwareMethods
{
    /**
     * @var Authentication
     */
    protected $authenticationService;

    /**
     * Get authentication service
     *
     * @return Authentication
     */
    public function getAuthenticationService()
    {
        if (!$this->authenticationService) {
            $service = $this->getContainer()->get('authentication');
            $this->setAuthenticationService($service);
        }
        return $this->authenticationService;
    }

    /**
     * Sets the authentication service
     *
     * @param Authentication $service
     *
     * @return $this|self|AuthenticationAwareInterface
     */
    public function setAuthenticationService(Authentication $service)
    {
        $this->authenticationService = $service;
        return $this;
    }

    /**
     * Get dependency container
     *
     * @return ContainerInterface
     */
    abstract public function getContainer();
}

<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Template\Extension;

use Slick\Template\EngineExtensionInterface;
use Slick\Template\Extension\AbstractTwigExtension;
use Slick\Users\Service\Authentication\AuthenticationAwareInterface;
use Slick\Users\Service\Authentication\AuthenticationAwareMethods;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Authentication template extension
 *
 * @package Slick\Users\Template\Extension
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Authentication extends AbstractTwigExtension implements
    EngineExtensionInterface,
    \Twig_Extension_GlobalsInterface,
    DependencyContainerAwareInterface,
    AuthenticationAwareInterface,
    SettingsAwareInterface
{

    /**
     * Used to get access to application dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Used to get access to the Authentication service
     */
    use AuthenticationAwareMethods;

    /**
     * Get access to settings
     */
    use SettingsAwareMethods;

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    function getName()
    {
        return 'Authentication extension';
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    function getGlobals()
    {
        return [
            'authentication' => $this->getAuthenticationService(),
            'serverAddress' => $this->getSettings()
                ->get('server.name', 'http://localhost')
        ];
    }
}

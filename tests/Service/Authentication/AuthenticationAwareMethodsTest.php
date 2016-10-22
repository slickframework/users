<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Authentication;

use Slick\Form\Element\ContainerInterface;
use Slick\Users\Service\Authentication;
use Slick\Users\Service\Authentication\AuthenticationAwareMethods;
use Slick\Users\Tests\TestCase;

class AuthenticationAwareMethodsTest extends TestCase
{

    use AuthenticationAwareMethods;

    /**
     * Get dependency container
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->getContainerMock(
            ['authentication' => \Phake::mock(Authentication::class)]
        );
    }

    /**
     * Should grab the authentication form container
     */
    public function testAuthenticationLazyLoad()
    {
        $this->assertInstanceOf(Authentication::class, $this->getAuthenticationService());
    }
}

<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Template;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Template\Extension\Authentication;

/**
 * Authentication Template extension test case
 *
 * @package Slick\Users\Tests\Template
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuthenticationTest extends TestCase
{

    /**
     * Should return the extension name
     */
    public function testExtensionName()
    {
        $name = 'Authentication extension';
        $extension = new Authentication();
        $this->assertEquals($name, $extension->getName());
        return $extension;
    }

    /**
     * Should return the authentication service
     *
     * @param Authentication $extension
     *
     * @depends testExtensionName
     */
    public function testGlobals(Authentication $extension)
    {
        $authentication = \Phake::mock(\Slick\Users\Service\Authentication::class);
        $extension->setAuthenticationService($authentication);
        $this->assertSame($authentication, $extension->getGlobals()['authentication']);
    }
}

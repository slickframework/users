<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Controller\Profile;
use Slick\Users\Form\ProfileFormInterface;

class ProfileTest extends ControllerTestCase
{

    /**
     * @var Profile
     */
    protected $controller;

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Profile();
        parent::setUp();
    }

    /**
     * Should use the form registry to lazy load the form
     */
    public function testProfileFormLazyLoad()
    {
        $form = $this->controller->getProfileForm();
        $this->assertInstanceOf(ProfileFormInterface::class, $form);
    }

    public function testProfileIndex()
    {
        $form = \Phake::mock(ProfileFormInterface::class);
        $this->controller->setProfileForm($form);
        $this->controller->index();
        $this->assertVarSame($form, 'profileForm');
        $this->assertViewEquals('accounts/profile');
    }
}

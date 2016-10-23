<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Psr\Log\LoggerInterface;
use Slick\Users\Controller\Profile;
use Slick\Users\Domain\Account;
use Slick\Users\Form\ProfileFormInterface;
use Slick\Users\Service\Account\ProfileUpdater;
use Slick\Users\Service\Account\ProfileUpdaterInterface;

/**
 * Profile Test Case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
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

    /**
     * Should set the form for display and the proper view for it
     * @test
     */
    public function testProfileIndex()
    {
        $form = \Phake::mock(ProfileFormInterface::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(false);
        $this->controller->setProfileForm($form);
        $this->controller->index();
        $this->assertVarSame($form, 'profileForm');
        $this->assertViewEquals('accounts/profile');
    }

    /**
     * Should check form submission and run account updater service
     * @test
     */
    public function testFormSubmission()
    {
        $account = \Phake::mock(Account::class);
        $form = \Phake::mock(ProfileFormInterface::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        \Phake::when($form)->isValid()->thenReturn(true);
        \Phake::when($form)->getAccount()->thenReturn($account);
        $this->controller->setProfileForm($form);
        $updater = \Phake::mock(ProfileUpdaterInterface::class);
        $this->controller->setProfileUpdater($updater);
        $this->controller->index();
        \Phake::verify($form, \Phake::times(2))->getAccount();
        \Phake::verify($updater, \Phake::times(1))->update($account);
    }

    /**
     * Should catch the exception and ouput an error message
     * @test
     */
    public function testExceptionOnSave()
    {
        $logger = \Phake::mock(LoggerInterface::class);
        $this->controller->setLogger($logger);
        $account = \Phake::mock(Account::class);
        $form = \Phake::mock(ProfileFormInterface::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        \Phake::when($form)->isValid()->thenReturn(true);
        \Phake::when($form)->getAccount()->thenReturn($account);
        $this->controller->setProfileForm($form);
        $updater = \Phake::mock(ProfileUpdaterInterface::class);
        \Phake::when($updater)->update($account)->thenThrow(new \Exception('Error!'));
        $this->controller->setProfileUpdater($updater);
        $this->controller->index();
        $this->assertErrorFlashMessageMatch('Error!');
        \Phake::verify($logger)->critical('Error!');
    }

    /**
     * Should get the profile updater from dependency container
     * @test
     */
    public function testUpdaterLazyLoad()
    {
        $updater = \Phake::mock(ProfileUpdater::class);
        $container = $this->getContainerMock(
            [
                'profileUpdater' => $updater
            ]
        );
        $this->controller->setContainer($container);
        $this->assertSame($updater, $this->controller->getProfileUpdater());
    }
}

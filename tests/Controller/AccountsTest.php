<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Interop\Container\ContainerInterface;
use Slick\Mvc\Form\EntityForm;
use Slick\Users\Controller\Accounts;
use Slick\Users\Service\Account\Register;

/**
 * Accounts controller test case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountsTest extends ControllerTestCase
{

    /**
     * @var Accounts
     */
    protected $controller;

    /**
     * Set the STU controller object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->controller = new Accounts();
    }

    /**
     * Should lazy load the registration form
     * @test
     */
    public function getRegistrationForm()
    {
        $form = $this->controller->getRegisterForm();
        $this->assertInstanceOf(EntityForm::class, $form);
    }

    /**
     * Should register form in the view vars
     * @test
     */
    public function registerFormOnVars()
    {
        $form = $this->getMockedObject(EntityForm::class);
        $form->expects($this->once())
            ->method('wasSubmitted')
            ->willReturn(false);
        $this->controller->setRegisterForm($form);
        $this->controller->signUp();
        $this->assertSame($form, $this->controller->getViewVars()['form']);
    }

    public function testInvalidForm()
    {
        $registerForm = \Phake::mock(EntityForm::class);
        \Phake::when($registerForm)->isValid()->thenReturn(false);
        \Phake::when($registerForm)->wasSubmitted()->thenReturn(true);
        $this->controller->setRegisterForm($registerForm);
        $this->controller->signUp();
        $this->assertErrorFlashMessageMatch('Unable to create new account.');
    }

    /**
     * Should lazy loads the service using the Di container
     * @test
     */
    public function getRegisterServiceFromDiContainer()
    {
        $service = \Phake::mock(Register::class);
        $container = \Phake::mock(ContainerInterface::class);
        \Phake::when($container)->get('accountRegister')->thenReturn($service);
        $this->controller->setContainer($container);
        $this->assertSame($service, $this->controller->getRegisterService());
    }

    /**
     * Should prepare the register request and use the register service to
     * effectively create a fully working account.
     * @test
     */
    public function registerAccount()
    {
        $data = [
            'name' => '',
            'email' => 'jon.doe@example.com',
            'password' => 'its-a-secret',
            'confirmPassword' => 'its-a-secret'
        ];
        $form = \Phake::mock(EntityForm::class);
        \Phake::when($form)->getData()->thenReturn($data);
        \Phake::when($form)->isValid()->thenReturn(true);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        $this->controller->setRegisterForm($form);
        $service = \Phake::mock(Register::class);
        $this->controller->setRegisterService($service);
        $this->controller->signUp();
        \Phake::verify($service)->execute($this->isInstanceOf(Register\RegisterRequest::class));
    }
}

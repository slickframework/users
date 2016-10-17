<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Psr\Log\LoggerInterface;
use Slick\Users\Controller\Login;
use Slick\Users\Form\LoginForm;
use Slick\Users\Service\Account\Authentication;

/**
 * Login controller test case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class LoginTest extends ControllerTestCase
{
    /**
     * @var Login
     */
    protected $controller;

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Login();
        parent::setUp();
    }

    /**
     * Get the logger from dependency container
     */
    public function testGetLogger()
    {
        $dep = ['logger' => \Phake::mock(LoggerInterface::class)];
        $this->controller->setContainer($this->getContainerMock($dep));
        $logger = $this->controller->getLogger();
        $this->assertSame($dep['logger'], $logger);
    }

    /**
     * Should get the form from Form registry
     * @test
     */
    public function getForm()
    {
        $form = $this->controller->getLoginForm();
        $this->assertInstanceOf(LoginForm::class, $form);
    }

    /**
     * Should use the dependency container to grab/create the authentication
     * service.
     *
     * @test
     */
    public function getLoginService()
    {
        $dep = [
            'accountAuthentication' => \Phake::mock(Authentication::class)
        ];
        $this->controller->setContainer($this->getContainerMock($dep));
        $auth = $this->controller->getAuthenticationService();
        $this->assertSame($dep['accountAuthentication'], $auth);
    }

    /**
     * Should set the form and view
     * @test
     */
    public function getSignInPage()
    {
        $form = \Phake::mock(LoginForm::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(false);
        $this->controller->setLoginForm($form);

        $this->controller->signIn();
        $this->assertViewEquals('accounts/sign-in');
    }

    /**
     * @test
     */
    public function loginSuccessful()
    {
        $form = \Phake::mock(LoginForm::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        \Phake::when($form)->isValid()->thenReturn(true);
        \Phake::when($form)->getData()->thenReturn(['username' => 'foo', 'password' => 'bar']);
        $this->controller->setLoginForm($form);
        $auth = \Phake::mock(Authentication::class);
        \Phake::when($auth)->login('foo', 'bar')->thenReturn(true);
        $dep = [
            'accountAuthentication' => $auth
        ];
        $this->controller->setContainer($this->getContainerMock($dep));
        $this->controller->signIn();
        \Phake::verify($auth)->login('foo', 'bar');
    }

    /**
     * @test
     */
    public function unsuccessfulLogin()
    {
        $form = \Phake::mock(LoginForm::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        \Phake::when($form)->isValid()->thenReturn(true);
        \Phake::when($form)->getData()->thenReturn(['username' => 'foo', 'password' => 'bar']);
        $this->controller->setLoginForm($form);
        $auth = \Phake::mock(Authentication::class);
        \Phake::when($auth)->login('foo', 'bar')->thenReturn(false);
        $dep = [
            'accountAuthentication' => $auth
        ];
        $this->controller->setContainer($this->getContainerMock($dep));
        $this->controller->signIn();
        \Phake::verify($auth)->login('foo', 'bar');
        $this->assertErrorFlashMessageMatch('Invalid credentials. Please try again.');
    }

    /**
     * @test
     */
    public function errorWhenVerifyingLogin()
    {
        $form = \Phake::mock(LoginForm::class);
        \Phake::when($form)->wasSubmitted()->thenReturn(true);
        \Phake::when($form)->isValid()->thenReturn(true);
        \Phake::when($form)->getData()->thenReturn(['username' => 'foo', 'password' => 'bar']);
        $this->controller->setLoginForm($form);
        $logger = \Phake::mock(LoggerInterface::class);

        $auth = \Phake::mock(Authentication::class);
        \Phake::when($auth)->login('foo', 'bar')->thenThrow(new \Exception('Error!'));
        $dep = [
            'accountAuthentication' => $auth,
            'logger' => $logger
        ];
        $this->controller->setContainer($this->getContainerMock($dep));
        $this->controller->signIn();
        \Phake::verify($logger)->alert('Error signing in account.', ['error' => 'Error!']);
        $this->assertErrorFlashMessageMatch('Error signing in.');
    }

}

<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Psr\Log\LoggerInterface;
use Slick\Users\Controller\Passwords;
use Slick\Users\Domain\Repository\TokenRepositoryInterface;
use Slick\Users\Domain\Token;
use Slick\Users\Exception\Accounts\InvalidTokenException;
use Slick\Users\Form\PasswordChangeFormInterface;
use Slick\Users\Service\Account\ChangePasswordInterface;

/**
 * Passwords Controller Test case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PasswordsTest extends ControllerTestCase
{

    /**
     * @var Passwords
     */
    protected $controller;

    const PASSWORD = 'MyS3cret!';

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Passwords();
        parent::setUp();
    }

    public function testFormLoading()
    {
        $form = $this->controller->getForm();
        $this->assertInstanceOf(PasswordChangeFormInterface::class, $form);
    }

    public function testGetRepository()
    {
        $repo = $this->controller->getTokenRepository();
        $this->assertInstanceOf(TokenRepositoryInterface::class, $repo);
    }

    public function testLoadPasswordService()
    {
        $service = \Phake::mock(ChangePasswordInterface::class);
        $dep = ['changePasswordService' => $service];
        $this->controller->setContainer($this->getContainerMock($dep));
        $this->assertSame($service, $this->controller->getChangePasswordService());
    }

    public function testTokenLoad()
    {
        $tokenStr = '12345abc';
        $response = $this->controller->getResponse();
        $request = $this->controller->getRequest()->withQueryParams(
            ['action' => Token::ACTION_RECOVER, 'token' => $tokenStr]
        );
        $this->controller->register($request, $response);
        $repo = \Phake::mock(TokenRepositoryInterface::class);
        $token = new Token();
        \Phake::when($repo)->getToken($tokenStr)->thenReturn($token);
        $this->controller->setTokenRepository($repo);
        $this->assertSame($token, $this->controller->getToken());
    }

    public function testSimpleRequest()
    {
        $form = $this->getMockedForm();
        $this->controller->setForm($form)
            ->change();
        $this->assertVarSame($form, 'form');
    }

    public function testInvalidForm()
    {
        $form = $this->getMockedForm(false, true);
        $this->controller->setForm($form)
            ->change();
        $this->assertErrorFlashMessageMatch('Password cannot be changed.');
        \Phake::verify($form)->isValid();
    }

    public function testInvalidTokenException()
    {
        $service = $this->getMockedService(new InvalidTokenException('error'));
        $logger = \Phake::mock(LoggerInterface::class);
        $form = $this->getMockedForm(true, true);
        $token = new Token();
        $this->controller
            ->setChangePasswordService($service)
            ->setForm($form)
            ->setLogger($logger)
            ->setToken($token)
            ->change();
        $this->assertErrorFlashMessageMatch('Invalid change password request.');
        \Phake::verify($logger)->alert(
            "invalid token",
            ['message' => 'error']
        );
        \Phake::verify($service)->setToken($token);
    }

    public function testException()
    {
        $service = $this->getMockedService(new \Exception('error'));
        $logger = \Phake::mock(LoggerInterface::class);
        $form = $this->getMockedForm(true, true);
        $token = new Token();
        $this->controller
            ->setChangePasswordService($service)
            ->setForm($form)
            ->setLogger($logger)
            ->setToken($token)
            ->change();
        $this->assertErrorFlashMessageMatch('Error while changing password');
        \Phake::verify($logger)->alert(
            "Error changing password after password recovery",
            ['message' => 'error']
        );
    }

    public function testSuccessRequest()
    {
        $service = $this->getMockedService();
        $logger = \Phake::mock(LoggerInterface::class);
        $form = $this->getMockedForm(true, true);
        $token = new Token();
        $this->controller
            ->setChangePasswordService($service)
            ->setForm($form)
            ->setLogger($logger)
            ->setToken($token)
            ->change();
        $this->assertSuccessFlashMessageMatch('Your password was successfully recovered.');
        $this->assertRedirectTo('/');
    }

    /**
     * Get a mocked service
     *
     * @param \Exception $exc
     * @return \Phake_IMock|ChangePasswordInterface
     */
    protected function getMockedService(\Exception $exc = null)
    {
        /** @var ChangePasswordInterface|\Phake_IMock $service */
        $service = \Phake::mock(ChangePasswordInterface::class, $this->getSelfAnswer());
        if ($exc) {
            \Phake::when($service)->change(self::PASSWORD)->thenThrow($exc);
        }
        return $service;
    }

    /**
     * Get a form mock
     *
     * @param bool $valid
     * @param bool $submitted
     *
     * @return \Phake_IMock|PasswordChangeFormInterface
     */
    protected function getMockedForm($valid = true, $submitted = false)
    {
        /** @var PasswordChangeFormInterface|\Phake_IMock $form */
        $form = \Phake::mock(PasswordChangeFormInterface::class);
        \Phake::when($form)->isValid()->thenReturn($valid);
        \Phake::when($form)->wasSubmitted()->thenReturn($submitted);
        \Phake::when($form)->getPassword()->thenReturn(self::PASSWORD);
        return $form;
    }
}

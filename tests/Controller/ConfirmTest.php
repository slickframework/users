<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Controller;

use Slick\Orm\RepositoryInterface;
use Slick\Users\Controller\Confirm;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\AccountEmailSenderInterface;

/**
 * Confirm controller test case
 *
 * @package Slick\Users\Tests\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ConfirmTest extends ControllerTestCase
{

    /**
     * @var Confirm
     */
    protected $controller;

    /**
     * Set the SUT controller object
     */
    protected function setUp()
    {
        $this->controller = new Confirm();
        parent::setUp();
    }

    /**
     * Lazy loads a repository for account entity
     * @test
     */
    public function testGetRepository()
    {
        $repo = $this->controller->getRepository();
        $this->assertInstanceOf(RepositoryInterface::class, $repo);
    }

    /**
     * Should lazy load the service from dependency container
     * @test
     */
    public function testGetEmailSenderService()
    {
        $service = \Phake::mock(AccountEmailSenderInterface::class);
        $dependencies = [
            'confirmEmailSender' => $service
        ];
        $container = $this->getContainerMock($dependencies);
        $this->controller->setContainer($container);
        $this->assertSame($service, $this->controller->getEmailSender());
    }

    /**
     * Should use the repository to grab the account with the id provided
     * in the route attributes.
     *
     * @test
     */
    public function getRequestedAccount()
    {
        $id = 12;
        $account = new Account();
        $attributes = ['id' => $id];
        $this->setRouteAttributes($attributes);
        $repository = \Phake::mock(RepositoryInterface::class);
        \Phake::when($repository)->get($id)->thenReturn($account);
        $this->controller->setRepository($repository);
        $this->assertSame($account, $this->controller->getAccount());
    }

    /**
     * Should grab the account, run the service, set flash message and redirect
     * to referer.
     *
     * @test
     */
    public function testHandleRequest()
    {
        $account = new Account;
        $response = $this->controller->getResponse();
        $request = $this->controller->getRequest()
            ->withHeader('referer', 'home');
        $this->controller->register($request, $response);

        $this->controller->setAccount($account);
        $service = \Phake::mock(AccountEmailSenderInterface::class);
        $this->controller->setEmailSender($service);

        $this->controller->handle();

        $this->assertSuccessFlashMessageMatch('An e-mail message will be');
        \Phake::verify($service)->sendTo($account);
        $this->assertRedirectTo('/');
    }
}

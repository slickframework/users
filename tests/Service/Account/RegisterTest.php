<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Service\Account\Register;
use Slick\Users\Tests\MockMethods;

/**
 * Register Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RegisterTest extends TestCase
{

    /**
     * @var Register
     */
    protected $service;

    /**
     * Register data
     * @var array
     */
    protected $postData = [
        'name' => 'Jon Doe',
        'email' => 'jon.doe@example.com',
        'password' => 'its-a-secret'
    ];

    /**
     * @var Register\RegisterRequest
     */
    protected $request;

    use MockMethods;

    /**
     * et the SUT service object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new Register();
        $this->request = new Register\RegisterRequest(
            $this->postData['email'],
            $this->postData['password'],
            $this->postData['name']
        );
        $this->service->registerRequest = $this->request;
    }

    /**
     * Should create a new account with posted data
     * @test
     */
    public function getAccount()
    {
        $account = $this->service->getAccount();
        $this->assertEquals($this->postData['email'], $account->email);
    }

    /**
     * Should create a new credential with posted data
     * @test
     */
    public function getCredential()
    {
        $credential = $this->service->getCredential();
        $this->assertEquals($this->postData['email'], $credential->email);
    }

    public function testExecution()
    {
        $account = $this->getMockedObject(Account::class);
        $account->expects($this->once())
            ->method('hydrate')
            ->willReturn($account);
        $account->expects($this->once())
            ->method('save')
            ->willReturn(1);
        $credential = $this->getMockedObject(Credential::class);
        $credential->expects($this->once())
            ->method('hydrate')
            ->willReturn($credential);
        $credential->expects($this->once())
            ->method('save')
            ->willReturn(1);
        $this->service->account = $account;
        $this->service->credential = $credential;
        $this->service->execute($this->request);
    }

}

<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use PHPUnit_Framework_TestCase as TestCase;
use Psr\Log\LoggerInterface;
use Slick\Orm\Repository\EntityRepository;
use Slick\Orm\Repository\QueryObject\QueryObjectInterface;
use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Credential;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Account\PasswordEncryptionService;

include_once 'functions.php';

/**
 * Authentication Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AuthenticationTest extends TestCase
{

    /**
     * @var Authentication
     */
    protected $service;

    /**
     * Sets the SUT authentication object
     */
    protected function setUp()
    {
        parent::setUp();
        $logger = \Phake::mock(LoggerInterface::class);
        $this->service = new Authentication($logger);
    }

    /**
     * Should lazy loads the account from ORM registry
     * @test
     */
    public function getAccountRepository()
    {
        $repo = $this->service->getRepository();
        $this->assertInstanceOf(EntityRepository::class, $repo);
    }

    /**
     * Should return false if no credential is found in the database
     * @test
     */
    public function invalidCredentials()
    {
        $username = 'test';
        $repository = $this->injectRepository($username);
        $this->assertFalse($this->service->login($username, ''));
        \Phake::verify($repository)->find();
    }

    /**
     * Should return true and set the account to be used
     * @test
     */
    public function successfulLogin()
    {
        $username = 'test';
        $account = new Account();
        $password = new PasswordEncryptionService('123456');
        $credential = new Credential(['account' => $account, 'password' => (string)$password]);
        $repository = $this->injectRepository($username, $credential);
        $this->assertTrue($this->service->login($username, '123456'));
        \Phake::verify($repository)->find();
        $this->assertSame($account, $this->service->getAccount());

    }

    /**
     * Inject repository mock with provided credential
     *
     * @param $username
     * @param null $credential
     *
     * @return \Phake_IMock|RepositoryInterface
     */
    protected function injectRepository($username, $credential = null)
    {
        $repository = \Phake::mock(RepositoryInterface::class);
        \Phake::when($repository)
            ->find()
            ->thenReturn($this->getQuery($username, $credential))
        ;
        $this->service->setRepository($repository);
        return $repository;
    }

    /**
     * Get query object mock
     *
     * @param string $username
     * @param null   $return
     *
     * @return \Phake_IMock|QueryObjectInterface
     */
    protected function getQuery($username, $return = null)
    {
        $query = \Phake::mock(QueryObjectInterface::class);
        \Phake::when($query)->where(
            [
                'credentials.email = :username OR
                     username = :username' => [
                    ':username' => $username
                ]
            ]
        )->thenReturn($query);
        \Phake::when($query)->first()->thenReturn($return);
        return $query;
    }
}

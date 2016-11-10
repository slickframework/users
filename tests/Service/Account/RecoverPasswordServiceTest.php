<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Account;

use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Repository\AccountsRepositoryInterface;
use Slick\Users\Service\Account\RecoverPasswordService;
use Slick\Users\Tests\TestCase;

/**
 * Recover Password Service Test Case
 *
 * @package Slick\Users\Tests\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RecoverPasswordServiceTest extends TestCase
{
    /**
     * @var RecoverPasswordService
     */
    protected $service;

    /**
     * Sets the SUT service object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->service = new RecoverPasswordService();
    }

    /**
     * Lazy loads repository for Account entity from Orm factory
     * @test
     */
    public function testGetAccountsRepository()
    {
        $repo = $this->service->getAccountRepository();
        $this->assertInstanceOf(AccountsRepositoryInterface::class, $repo);
    }
}

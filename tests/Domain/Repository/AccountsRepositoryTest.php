<?php

/**
 * This file is part of slick/users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Domain\Repository;

use Slick\Orm\Repository\QueryObject\QueryObjectInterface;
use Slick\Users\Domain\Repository\AccountsRepository;
use Slick\Users\Tests\TestCase;

/**
 * Accounts Repository Test
 *
 * @package Slick\Users\Tests\Domain\Repository
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class AccountsRepositoryTest extends TestCase
{

    /**
     * Should do a query to retrieve an account by its e-mail address
     * @test
     */
    public function testGetByEmail()
    {
        $email = 'jon.doe@example.com';
        $query = \Phake::mock(
            QueryObjectInterface::class,
            $this->getSelfAnswer()
        );
        /** @var AccountsRepository|\Phake_IMock $repo */
        $repo = \Phake::partialMock(AccountsRepository::class);
        \Phake::when($repo)->find()->thenReturn($query);
        $repo->getByEmail($email);
        \Phake::verify($query)->where(['email = :email' => [':email' => $email]]);
    }
}

<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Domain\Repository;

use Slick\Database\Adapter\AdapterInterface;
use Slick\Database\Sql\Delete;
use Slick\Orm\Orm;
use Slick\Orm\Repository\QueryObject\QueryObject;
use Slick\Orm\Repository\QueryObject\QueryObjectInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Repository\TokenRepository;
use Slick\Users\Domain\Token;
use Slick\Users\Tests\TestCase;

/**
 * Token Repository Test Case
 *
 * @package Slick\Users\Tests\Domain\Repository
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TokenRepositoryTest extends TestCase
{


    public function testCreation()
    {
        $repo = Orm::getRepository(Token::class);
        $this->assertInstanceOf(TokenRepository::class, $repo);
    }

    public function testLoadToken()
    {
        $token = new Token();
        $query = \Phake::mock(QueryObject::class, $this->getSelfAnswer());
        \Phake::when($query)->first()->thenReturn($token);
        /** @var TokenRepository|\Phake_IMock $repo */
        $repo = \Phake::partialMock(TokenRepository::class);
        \Phake::when($repo)->find()->thenReturn($query);
        $this->assertSame($token, $repo->getToken('test:12345abc'));
        \Phake::verify($query)->where(['selector = :key' => [':key' => 'test']]);
        \Phake::verify($query)->order('tokens.ttl DESC');
    }

    public function testAccountTokensDelete()
    {
        $adapter = \Phake::mock(AdapterInterface::class);
        $repo = new TokenRepository();
        $repo->setAdapter($adapter);
        $repo->deleteAccountTokens(new Token(['account' => new Account(['id' => 123])]));
        \Phake::verify($adapter)->execute(
            $this->isInstanceOf(Delete::class),
            [
                ':id' => 123
            ]
        );
    }
}

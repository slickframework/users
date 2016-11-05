<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Domain\Repository;

use Slick\Orm\Orm;
use Slick\Orm\Repository\QueryObject\QueryObject;
use Slick\Orm\Repository\QueryObject\QueryObjectInterface;
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
        $this->assertSame($token, $repo->getToken('test'));
        \Phake::verify($query)->where(['token = :tkn' => [':tkn' => 'test']]);
        \Phake::verify($query)->order('tokens.ttl DESC');
    }
}

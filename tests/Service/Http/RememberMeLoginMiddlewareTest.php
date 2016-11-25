<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Repository\TokenRepositoryInterface;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Http\RememberMeLoginMiddleware;
use Slick\Users\Tests\TestCase;

/**
 * RememberMeLoginMiddlewareTest
 *
 * @package Slick\Users\Tests\Service\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RememberMeLoginMiddlewareTest extends TestCase
{

    /**
     * @var RememberMeLoginMiddleware|\Phake_IMock
     */
    protected $middleware;

    /**
     * Setting the SUT middleware object
     */
    protected function setUp()
    {
        parent::setUp();
        $response = \Phake::mock(ResponseInterface::class);
        $this->middleware = \Phake::partialMock(RememberMeLoginMiddleware::class);
        \Phake::when($this->middleware)
            ->executeNext(
                $this->isInstanceOf(RequestInterface::class),
                $this->isInstanceOf(ResponseInterface::class)
            )
            ->thenReturn($response)
        ;
    }

    /**
     * Should execute ancestor executeNext() to keep the execution flow
     * @test
     */
    public function testMaintainChain()
    {
        $this->setValidSignInData(false);
        $request = \Phake::mock(ServerRequestInterface::class);
        $response = \Phake::mock(ResponseInterface::class);
        $this->middleware->handle($request, $response);
        \Phake::verify($this->middleware)->executeNext($request, $response);
    }

    public function testLoginWithCookie()
    {
        $this->setValidSignInData(true);
        $account = new Account();
        $token = \Phake::partialMock(Token::class, [
            'action' => Token::ACTION_REMEMBER,
            'account' => $account
        ]);
        \Phake::when($token)->isValid()->thenReturn(true);
        \Phake::when($token)->save()->thenReturn(1);
        \Phake::when($token)->delete()->thenReturn(1);
        $this->middleware->setToken($token);

        $request = \Phake::mock(ServerRequestInterface::class);
        $response = \Phake::mock(ResponseInterface::class);

        $newToken = \Phake::mock(Token::class);
        \Phake::when($newToken)->save()->thenReturn(1);
        $this->middleware->setNewToken($newToken);

        $this->middleware->handle($request, $response);
        $data = $_SESSION['slick_'.Authentication::SESSION_KEY];
        $this->assertInstanceOf(Authentication\SignedInAccount::class, $data);
        $this->assertSame($account, $data->account);
        \Phake::verify($token)->delete();
    }

    public function testGetNewToken()
    {
        $account = new Account();
        $token = new Token(['account' => $account]);
        $this->middleware->setToken($token);
        $newToken = $this->middleware->getNewToken();
        $this->assertSame($account, $newToken->account);
    }

    /**
     * Should look for remember me cookie in COOKIE super global
     * @test
     */
    public function testGetCookie()
    {
        $value = 'key:test12345';
        $_COOKIE['users-rmm'] = $value;
        $this->assertEquals($value, $this->middleware->getCookie());
    }

    /**
     * It can sign in only when its a guest user and has a cookie set
     * @test
     */
    public function testTrySignIn()
    {
        $this->setValidSignInData();
        $this->assertTrue($this->middleware->canSignIn());
    }

    /**
     * Should use ORM to gain access to tokens repository
     * @test
     */
    public function testGetTokenRepo()
    {
        $repo = $this->middleware->getTokensRepository();
        $this->assertInstanceOf(TokenRepositoryInterface::class, $repo);
    }

    /**
     * Should use the tokens repository and the cookie value to return
     * a valid token.
     *
     * @test
     */
    public function testToken()
    {
        $value = $this->setValidSignInData();
        $token = \Phake::partialMock(Token::class, ['action' => Token::ACTION_REMEMBER]);
        \Phake::when($token)->delete()->thenReturn(1);
        $repo = \Phake::mock(TokenRepositoryInterface::class);
        \Phake::when($repo)->getToken($value)->thenReturn($token);
        $this->middleware->setTokensRepository($repo);
        $this->assertSame($token, $this->middleware->getToken());
    }

    /**
     * Sets valid sign in data and return the cookie value
     *
     * @param boolean $valid
     *
     * @return string
     */
    protected function setValidSignInData($valid = true)
    {
        $value = 'key:test12345';
        $_COOKIE['users-rmm'] = $value;
        if (!$value) unset($_COOKIE['users-rmm']);
        $data = new Authentication\SignedInAccount(['guest' => $valid]);
        $_SESSION['slick_'.Authentication::SESSION_KEY] = $data;
        return $value;
    }
}

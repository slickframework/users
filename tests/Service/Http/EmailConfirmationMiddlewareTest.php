<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Tests\Service\Http;

use Slick\Http\PhpEnvironment\Request;
use Slick\Http\Response;
use Slick\Http\SessionDriverInterface;
use Slick\Mvc\Http\FlashMessages;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Repository\TokenRepository;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Authentication;
use Slick\Users\Service\Http\EmailConfirmationMiddleware;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Tests\TestCase;

/**
 * Email Confirmation Middleware Test Case
 *
 * @package Slick\Users\Tests\Service\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class EmailConfirmationMiddlewareTest extends TestCase
{

    /**
     * @var EmailConfirmationMiddleware
     */
    protected $middleWare;

    /**
     * Set the SUT middleware object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->middleWare = new EmailConfirmationMiddleware();
    }

    /**
     * Should use the ORM factory to get the repository
     * @test
     */
    public function testGetRepository()
    {
        $repo = $this->middleWare->getRepository();
        $this->assertInstanceOf(TokenRepository::class, $repo);
    }

    public function testNoTokenSet()
    {
        $response = new Response();
        $request = (new Request())
            ->withQueryParams([]);
        $this->middleWare->handle($request, $response);
        $this->assertNull($this->middleWare->getToken());
    }

    public function testInvalidToken()
    {
        $response = new Response();
        $request = (new Request())
            ->withQueryParams([
                'action' => 'confirm',
                'token' => 'test'
            ]);
        /** @var TokenRepository|\Phake_IMock $repo */
        $repo = \Phake::mock(TokenRepository::class);
        \Phake::when($repo)->getToken('test')->thenReturn(new Token(['ttl' => '2015-09-09']));
        $this->middleWare->setRepository($repo);
        $this->middleWare->handle($request, $response);
        $this->assertNull($this->middleWare->getToken());
    }

    public function testValidToken()
    {
        $tokenAccount = \Phake::partialMock(Account::class, ['id' => 2]);
        \Phake::when($tokenAccount)->save()->thenReturn(0);

        $flashMessages = $this->setMockFlashMessages();

        $response = new Response();
        $request = (new Request())
            ->withQueryParams([
                'action' => 'confirm',
                'token' => 'test:12345abc'
            ])
        ;

        /** @var TokenRepository|\Phake_IMock $repo */
        $repo = \Phake::mock(TokenRepository::class);
        \Phake::when($repo)->getToken('test:12345abc')->thenReturn($this->getValidToken($tokenAccount));
        $this->middleWare->setRepository($repo);

        $this->middleWare->handle($request, $response);

        \Phake::verify($flashMessages)->set(0, 'Your e-mail address was successfully confirmed.');
        \Phake::verify($tokenAccount)->save();
    }

    public function testInvalidTokenValue()
    {
        $flashMessages = $this->setMockFlashMessages();

        $response = new Response();
        $request = (new Request())
            ->withQueryParams([
                'action' => 'confirm',
                'token' => 'test:12345abc'
            ])
        ;

        $token = $this->getValidToken(new Account());
        $token->validate('abc123456');

        /** @var TokenRepository|\Phake_IMock $repo */
        $repo = \Phake::mock(TokenRepository::class);
        \Phake::when($repo)->getToken('test:12345abc')->thenReturn($token);
        $this->middleWare->setRepository($repo);

        $this->middleWare->handle($request, $response);

        \Phake::verify($flashMessages)->set(
            1,
            'Possible token theft detected. For your account data security ' .
            'all account generated tokens were delete.'
        );
    }

    public function testValidTokenCurrentUser()
    {
        $loggedIn = new Account(['id' => 2]);
        $tokenAccount = \Phake::partialMock(Account::class, ['id' => 2]);
        \Phake::when($tokenAccount)->save()->thenReturn(0);
        $data = (object)['account' => $loggedIn];

        $session = \Phake::mock(SessionDriverInterface::class);
        \Phake::when($session)->get('sign-in-data')->thenReturn($data);

        $authentication = \Phake::mock(Authentication::class);
        \Phake::when($authentication)->getCurrentAccount()->thenReturn($loggedIn);
        \Phake::when($authentication)->getSession()->thenReturn($session);
        $this->middleWare->setAuthenticationService($authentication);

        $flashMessages = $this->setMockFlashMessages();

        $response = new Response();
        $request = (new Request())
            ->withQueryParams([
                'action' => 'confirm',
                'token' => 'test:12345abc'
            ]);

        /** @var TokenRepository|\Phake_IMock $repo */
        $repo = \Phake::mock(TokenRepository::class);
        \Phake::when($repo)->getToken('test:12345abc')->thenReturn($this->getValidToken($tokenAccount));
        $this->middleWare->setRepository($repo);

        $this->middleWare->handle($request, $response);

        \Phake::verify($flashMessages)->set(0, 'Your e-mail address was successfully confirmed.');
        \Phake::verify($tokenAccount)->save();
    }

    /**
     * @return \Phake_IMock|FlashMessages
     */
    protected function setMockFlashMessages()
    {
        $flashMessages = \Phake::mock(FlashMessages::class);
        $this->middleWare->setFlashMessages($flashMessages);
        return $flashMessages;
    }

    /**
     * Get a partial mocked and valid token
     *
     * @param Account $account
     * @return \Phake_IMock|Token
     */
    protected function getValidToken(Account $account)
    {
        $token = \Phake::partialMock(Token::class,
            [
                'ttl' => new DateTime('+3 hours'),
                'account' => $account
            ]
        );
        $token->token = hash('sha256', '12345abc');
        $token->validate('12345abc');
        \Phake::when($token)->delete()->thenReturn(0);
        return $token;
    }
}

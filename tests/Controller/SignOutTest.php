<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller {

    use Slick\Users\Controller\SignOut;
    use Slick\Users\Domain\Repository\TokenRepositoryInterface;
    use Slick\Users\Domain\Token;
    use Slick\Users\Service\Account\CookieTokenStorageInterface;
    use Slick\Users\Tests\Controller\ControllerTestCase;

    /**
     * SignOut controller test case
     *
     * @package Slick\Users\Tests\Controller
     * @author  Filipe Silva <silvam.filipe@gmail.com>
     */
    class SignOutTest extends ControllerTestCase
    {

        /**
         * @var SignOutController
         */
        protected $controller;

        protected function setUp()
        {
            $this->controller = new SignOutController();
            parent::setUp();
        }

        public function testHandle()
        {
            $value = 'key:test12345';
            $_COOKIE['users-rmm'] = $value;
            $token = \Phake::partialMock(Token::class);
            \Phake::when($token)->delete()->thenReturn(1);
            $cookieService = \Phake::mock(CookieTokenStorageInterface::class);

            $this->controller
                ->setToken($token)
                ->setCookieService($cookieService)
                ->handle();

            $this->assertRedirectTo('/');
            \Phake::verify($cookieService)->erase('users-rmm');
            \Phake::verify($token)->delete();
        }

        /**
         * Should create a cookie service in a lazy load fashion
         * @test
         */
        public function testGetCookieService()
        {
            $cookieService = $this->controller->getCookieService();
            $this->assertInstanceOf(
                CookieTokenStorageInterface::class,
                $cookieService
            );
        }

        /**
         * Should look for remember me cookie in COOKIE super global
         * @test
         */
        public function testGetCookie()
        {
            $value = 'key:test12345';
            $_COOKIE['users-rmm'] = $value;
            $this->assertEquals($value, $this->controller->getCookie());
        }

        public function testTokensRepository()
        {
            $repo = $this->controller->getTokensRepository();
            $this->assertInstanceOf(TokenRepositoryInterface::class, $repo);
        }

        public function testGetToken()
        {
            $token = \Phake::mock(Token::class);
            $value = 'key:test12345';
            $_COOKIE['users-rmm'] = $value;
            $repo = \Phake::mock(TokenRepositoryInterface::class);
            \Phake::when($repo)->getToken($value)->thenReturn($token);
            $this->controller->setTokensRepository($repo);
            $this->assertSame($token, $this->controller->getToken());
        }
    }

    /**
     * Mocker session regenerate ID
     *
     * @param $renew
     */
    function session_regenerate_id($renew)
    {
        \PHPUnit_Framework_Assert::assertTrue($renew);
    }

    /**
     * SignOutController
     *
     * @package Slick\Users\Tests\Controller
     * @author  Filipe Silva <silvam.filipe@gmail.com>
     */
    class SignOutController extends SignOut {}
}

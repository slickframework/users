<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server\AbstractMiddleware;
use Slick\Http\Server\MiddlewareInterface;
use Slick\Orm\Orm;
use Slick\Users\Domain\Repository\TokenRepositoryInterface;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Account\CookieTokenStorageInterface;
use Slick\Users\Service\Account\CookieTokenStorageService;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;
use Slick\Users\Shared\DataType\DateTime;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;
use Slick\Users\Shared\Http\Session\SessionAwareInterface;
use Slick\Users\Shared\Http\Session\SessionAwareMethods;

/**
 * Remember Me Login Middleware
 *
 * @package Slick\Users\Service\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RememberMeLoginMiddleware extends AbstractMiddleware implements
    MiddlewareInterface,
    DependencyContainerAwareInterface,
    SettingsAwareInterface,
    SessionAwareInterface
{

    /**
     * Used to access dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Used to gain access to application settings
     */
    use SettingsAwareMethods;

    /**
     * To gain access to session data
     */
    use SessionAwareMethods;

    /**
     * @var string
     */
    protected $cookie;

    /**
     * @var TokenRepositoryInterface
     */
    protected $tokensRepository;

    /**
     * @var Token
     */
    protected $token;

    /**
     * @var Token
     */
    protected $newToken;

    /**
     * @var CookieTokenStorageInterface
     */
    protected $cookieStorageService;

    /**
     * Handles a Request and updated the response
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function handle(
        ServerRequestInterface $request, ResponseInterface $response
    )
    {
        if ($this->canSignIn()) {
            $this->signIn();
        }
        return $this->executeNext($request, $response);
    }

    /**
     * Tries to sign in the account from a valid token
     */
    protected function signIn()
    {
        $token = $this->getToken();
        if ($token && $token->isValid()) {
            $data = new Authentication\SignedInAccount(
                [
                    'account' => $token->account,
                    'dirty' => true
                ]
            );
            $this->getSession()->set(Authentication::SESSION_KEY, $data);
            $token->delete();
            $this->resetToken();
        }
    }

    /**
     * Sets the new token for this user.
     */
    public function resetToken()
    {
        $name = $this->getSettings()->get('rememberMe.cookie', 'rmm');
        $this->getCookieStorageService()
            ->set($name, $this->getNewToken());
        $this->getNewToken()->save();
    }

    /**
     * Get cookie
     *
     * @return string
     */
    public function getCookie()
    {
        if (null == $this->cookie) {
            $key = $this->getSettings()->get('rememberMe.cookie', 'rmm');
            $cookie = array_key_exists($key, $_COOKIE)
                ? $_COOKIE[$key]
                : null;
            $this->setCookie($cookie);
        }
        return $this->cookie;
    }

    /**
     * Set cookie
     *
     * @param string $cookie
     * @return RememberMeLoginMiddleware
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * Check if in current request we can try to sign in
     *
     * @return bool
     */
    public function canSignIn()
    {
        /** @var Authentication\SignedInAccount $accountData */
        $accountData = $this->getSession()
            ->get(
                Authentication::SESSION_KEY,
                new Authentication\SignedInAccount([
                    'guest' => true,
                    'signedIn' => false
                ])
            );
        return (
            $accountData->guest &&
            $this->getCookie() !== null
        );
    }

    /**
     * Get tokens repository
     *
     * @return TokenRepositoryInterface
     */
    public function getTokensRepository()
    {
        if (!$this->tokensRepository) {
            $this->setTokensRepository(Orm::getRepository(Token::class));
        }
        return $this->tokensRepository;
    }

    /**
     * Set tokens repository
     *
     * @param TokenRepositoryInterface $tokensRepository
     *
     * @return RememberMeLoginMiddleware
     */
    public function setTokensRepository(
        TokenRepositoryInterface $tokensRepository
    ) {
        $this->tokensRepository = $tokensRepository;
        return $this;
    }

    /**
     * Get token
     *
     * @return Token
     */
    public function getToken()
    {
        if (!$this->token) {
            $token = $this->getTokensRepository()
                ->getToken($this->getCookie())
            ;
            $this->setToken($token);
        }
        return $this->token;
    }

    /**
     * Set token
     *
     * @param Token $token
     *
     * @return RememberMeLoginMiddleware
     */
    public function setToken(Token $token = null)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get new token
     *
     * @return Token
     */
    public function getNewToken()
    {
        if (!$this->newToken) {
            $expire = $this->getSettings()
                ->get('rememberMe.expire', 30*24*60*60);
            $ttl = new DateTime(time()+$expire);
            $token = new Token(
                [
                    'account' => $this->getToken()->account,
                    'action' => Token::ACTION_REMEMBER,
                    'ttl' => $ttl
                ]
            );
            $this->setNewToken($token);
        }
        return $this->newToken;
    }

    /**
     * Set newToken property
     *
     * @param Token $newToken
     * @return RememberMeLoginMiddleware
     */
    public function setNewToken(Token $newToken)
    {
        $this->newToken = $newToken;
        return $this;
    }

    /**
     * Get cookie storage service
     *
     * @return CookieTokenStorageInterface
     */
    public function getCookieStorageService()
    {
        if (!$this->cookieStorageService) {
            $this->setCookieStorageService(new CookieTokenStorageService());
        }
        return $this->cookieStorageService;
    }

    /**
     * Set cookie storage service
     *
     * @param CookieTokenStorageInterface $cookieStorageService
     *
     * @return RememberMeLoginMiddleware
     */
    public function setCookieStorageService(
        CookieTokenStorageInterface $cookieStorageService
    ) {
        $this->cookieStorageService = $cookieStorageService;
        return $this;
    }

}

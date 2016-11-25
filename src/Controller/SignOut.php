<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Mvc\Controller;
use Slick\Orm\Orm;
use Slick\Users\Domain\Repository\TokenRepositoryInterface;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\CookieTokenStorageInterface;
use Slick\Users\Service\Account\CookieTokenStorageService;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;

/**
 * SignOut
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SignOut extends Controller implements SettingsAwareInterface
{

    /**
     * @var CookieTokenStorageInterface
     */
    protected $cookieService;

    /**
     * @var string
     */
    protected $cookie;

    /**
     * @var Token
     */
    protected $token;

    /**
     * @var TokenRepositoryInterface
     */
    protected $tokensRepository;

    /**
     * Needed to access application settings
     */
    use SettingsAwareMethods;

    /**
     * Handles the request to logout a user
     */
    public function handle()
    {
        session_regenerate_id(true);
        $_SESSION = [];
        $this->deleteCookie();
        $this->deleteToken();
        $this->redirect('home');
    }

    /**
     * Deletes remember me cookie
     */
    protected function deleteCookie()
    {
        if ($this->getCookie() !== null) {
            $key = $this->getSettings()->get('rememberMe.cookie', 'rmm');
            $this->getCookieService()->erase($key);
        }
    }

    /**
     * Deletes remember me token
     */
    protected function deleteToken()
    {
        if ($this->getToken() instanceof Token) {
            $this->getToken()->delete();
        }
    }

    /**
     * Get cookie service
     *
     * @return CookieTokenStorageInterface
     */
    public function getCookieService()
    {
        if (!$this->cookieService) {
            $this->setCookieService(new CookieTokenStorageService());
        }
        return $this->cookieService;
    }

    /**
     * Set cookie service
     *
     * @param CookieTokenStorageInterface $cookieService
     * @return SignOut
     */
    public function setCookieService(
        CookieTokenStorageInterface $cookieService
    ) {
        $this->cookieService = $cookieService;
        return $this;
    }

    /**
     * Get current cookie
     *
     * @return string
     */
    public function getCookie()
    {
        if (null === $this->cookie) {
            $key = $this->getSettings()->get('rememberMe.cookie', 'rmm');
            $this->cookie = array_key_exists($key, $_COOKIE)
                ? $_COOKIE[$key]
                : null;
        }
        return $this->cookie;
    }

    /**
     * Get token repository
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
     * Set token repository
     *
     * @param TokenRepositoryInterface $tokensRepository
     *
     * @return SignOut
     */
    public function setTokensRepository(
        TokenRepositoryInterface $tokensRepository
    ) {
        $this->tokensRepository = $tokensRepository;
        return $this;
    }

    /**
     * Get token form cookie
     *
     * @return Token
     */
    public function getToken()
    {
        if (!$this->token && null != $this->getCookie()) {
            $this->setToken(
                $this->getTokensRepository()->getToken($this->getCookie())
            );
        }
        return $this->token;
    }

    /**
     * Set token
     *
     * @param Token $token
     *
     * @return SignOut
     */
    public function setToken(Token $token)
    {
        $this->token = $token;
        return $this;
    }
}

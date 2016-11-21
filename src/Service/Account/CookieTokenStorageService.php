<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Users\Domain\Token;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;

/**
 * Cookie Token Storage Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class CookieTokenStorageService implements
    CookieTokenStorageInterface,
    SettingsAwareInterface
{

    /**
     * To grab settings for remember me cookie
     */
    use SettingsAwareMethods;

    /**
     * Set a cookie with provided token
     *
     * @param string $name
     * @param Token $token
     *
     * @return CookieTokenStorageService|CookieTokenStorageInterface
     */
    public function set($name, Token $token)
    {
        $expire = $this->getSettings()->get('rememberMe.expire', 3600);
        $expire = time() + $expire;
        setcookie($name, $token->getPublicToken(), $expire, '/');
        return $this;
    }

    /**
     * Erases the cookie with provided name
     *
     * @param string $name
     *
     * @return self|$this|CookieTokenStorageInterface
     */
    public function erase($name)
    {
        setcookie($name, '', time()-3600, '/');
        return $this;
    }
}

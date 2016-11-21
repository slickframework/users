<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Users\Domain\Token;

/**
 * Cookie Token Interface
 *
 * Stores account tokens in cookies
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface CookieTokenStorageInterface
{

    /**
     * Set a cookie with provided token
     *
     * @param string $name
     * @param Token  $token
     *
     * @return self|$this|CookieTokenStorageInterface
     */
    public function set($name, Token $token);

    /**
     * Erases the cookie with provided name
     *
     * @param string $name
     *
     * @return self|$this|CookieTokenStorageInterface
     */
    public function erase($name);
}

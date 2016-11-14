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
 * Change Password Interface
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ChangePasswordInterface
{

    /**
     * Sets the token from the request
     *
     * @param Token $token
     * @return self|$this|ChangePasswordInterface
     */
    public function setToken(Token $token);

    /**
     * Change the password for provided account
     *
     * @param $password
     * @return self|$this|ChangePasswordInterface
     */
    public function change($password);
}

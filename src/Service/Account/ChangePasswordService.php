<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;


use Slick\Users\Domain\Token;
use Slick\Users\Exception\Accounts\InvalidTokenException;

class ChangePasswordService extends AccountService implements ChangePasswordInterface
{
    /**
     * @var Token
     */
    protected $token;

    /**
     * Sets the token from the request
     *
     * @param Token $token
     * @return ChangePasswordService|ChangePasswordInterface
     */
    public function setToken(Token $token = null)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Change the password for provided account
     *
     * @param $password
     * @return ChangePasswordService|ChangePasswordInterface
     */
    public function change($password)
    {
        if (is_null($this->token) || !$this->token->isValid()) {
            throw new InvalidTokenException(
                "Invalid token given."
            );
        }

        return $this;
    }
}

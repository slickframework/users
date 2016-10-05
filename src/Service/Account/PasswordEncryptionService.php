<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

/**
 * Password Encryption Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PasswordEncryptionService
{
    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    protected $hash;

    /**
     * Password entropy cost
     */
    const COST = 13;

    /**
     * Password Encryption Service
     *
     * @param string $plainTextPassword
     */
    public function __construct($plainTextPassword)
    {
        $this->password  = $plainTextPassword;
    }

    /**
     * Computes the password hash and returns it
     *
     * @return string
     */
    public function hash()
    {
        if (!$this->hash) {
            $this->hash = password_hash(
                $this->password,
                PASSWORD_BCRYPT,
                ['cost' => self::COST]
            );
        }
        return $this->hash;
    }

    /**
     * Check if hash matches provided password
     *
     * @param string $hash
     *
     * @return bool
     */
    public function match($hash)
    {
        return (boolean) password_verify($this->password, $hash);
    }

    /**
     * Returns the hashed version of provided password
     *
     * @return string
     */
    public function __toString()
    {
        return $this->hash();
    }
}

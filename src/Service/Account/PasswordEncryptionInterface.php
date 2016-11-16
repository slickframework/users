<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

/**
 * Password Encryption Interface
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface PasswordEncryptionInterface
{

    /**
     * Set plain password to be hashed
     *
     * @param string $plainPassword
     *
     * @return self|$this|PasswordEncryptionInterface
     */
    public function setPassword($plainPassword);

    /**
     * Computes the password hash and returns it
     *
     * @return string
     */
    public function hash();

    /**
     * Check if hash matches provided password
     *
     * @param string $hash
     *
     * @return bool
     */
    public function match($hash);

    /**
     * Returns the hashed version of provided password
     *
     * @return string
     */
    public function __toString();
}

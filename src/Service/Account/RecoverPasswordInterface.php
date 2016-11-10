<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Users\Exception\Accounts\UnknownEmailException;

/**
 * Recover Password Interface
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface RecoverPasswordInterface
{

    /**
     * Sends out the e-mail message to the provided email address
     *
     * @return self|$this|RecoverPasswordInterface
     *
     * @throws UnknownEmailException
     */
    public function requestEmail();

    /**
     * Set e-mail address
     *
     * @param string $email
     *
     * @return self|$this|RecoverPasswordInterface
     */
    public function setEmail($email);
}

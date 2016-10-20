<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Slick\Form\FormInterface;
use Slick\Users\Domain\Account;

/**
 * Profile Form Interface
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ProfileFormInterface extends FormInterface
{

    /**
     * Set internal account
     *
     * @param Account $account
     *
     * @return ProfileFormInterface|self|$this
     */
    public function setAccount(Account $account);

    /**
     * Get current profile account
     *
     * @return Account
     */
    public function getAccount();

}
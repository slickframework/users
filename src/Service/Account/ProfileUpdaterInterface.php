<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use Slick\Users\Domain\Account;

/**
 * Profile Updater Interface
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ProfileUpdaterInterface
{

    /**
     * Updates the provided CHANGED account
     *
     * @param Account $account
     *
     * @return $this|ProfileUpdaterInterface
     */
    public function update(Account $account);
}

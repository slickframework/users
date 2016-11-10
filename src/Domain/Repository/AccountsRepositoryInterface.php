<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain\Repository;

use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Account;

/**
 * Accounts Repository Interface
 *
 * @package Slick\Users\Domain\Repository
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface AccountsRepositoryInterface extends RepositoryInterface
{

    /**
     * Get an account by its e-mail address
     *
     * @param string $email
     * @return Account|null
     */
    public function getByEmail($email);
}

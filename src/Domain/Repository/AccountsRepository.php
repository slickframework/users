<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Domain\Repository;

use Slick\Orm\Repository\EntityRepository;
use Slick\Users\Domain\Account;

/**
 * Accounts Repository
 *
 * @package Slick\Users\Domain\Repository
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AccountsRepository extends EntityRepository implements
    AccountsRepositoryInterface
{

    /**
     * Get an account by its e-mail address
     *
     * @param string $email
     * @return Account|null
     */
    public function getByEmail($email)
    {
        return $this->find()
            ->where(['email = :email' => [':email' => $email]])
            ->first();
    }
}

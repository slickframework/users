<?php

/**
 * This file is part of slick/users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Filter\StaticFilter;
use Slick\Orm\Orm;
use Slick\Users\Domain\Account;
use Slick\Users\Domain\Repository\AccountsRepositoryInterface;
use Slick\Users\Shared\Controller\BaseController;

/**
 * Account Aware Controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AccountAwareController extends BaseController
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var AccountsRepositoryInterface
     */
    protected $accountsRepository;

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $id = $this->getRouteAttributes('id', 0);
            $id = StaticFilter::filter('text', $id);
            /** @var Account $account */
            $account = $this->getAccountsRepository()->get($id);
            $this->setAccount($account);
        }
        return $this->account;
    }

    /**
     * Sets account property
     *
     * @param Account $account
     *
     * @return self|$this|AccountAwareController
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get account repository
     *
     * @return AccountsRepositoryInterface
     */
    public function getAccountsRepository()
    {
        if (!$this->accountsRepository) {
            $this->setAccountsRepository(Orm::getRepository(Account::class));
        }
        return $this->accountsRepository;
    }

    /**
     * Set account repository
     *
     * @param AccountsRepositoryInterface $accountsRepository
     *
     * @return self|$this|AccountAwareController
     */
    public function setAccountsRepository(
        AccountsRepositoryInterface $accountsRepository
    ) {
        $this->accountsRepository = $accountsRepository;
        return $this;
    }
}

<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Filter\StaticFilter;
use Slick\Http\Stream;
use Slick\Mvc\Controller;
use Slick\Orm\Orm;
use Slick\Orm\RepositoryInterface;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Account\Email\AccountEmailSenderInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Confirm e-mail request controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Confirm extends Controller implements DependencyContainerAwareInterface
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var AccountEmailSenderInterface
     */
    protected $emailSender;

    /**
     * Needed to access dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Handles the request to send a confirmation e-mail to provided account
     */
    public function handle()
    {
        $this->disableRendering();
        $account = $this->getAccount();
        $data = ['sent' => false];
        if ($account instanceof Account) {
            $data['sent'] = true;
            $this->getEmailSender()->sendTo($account);
        }

        $body = new Stream('php://memory', 'rw+');
        $body->write(json_encode($data));
        $this->response = $this->response
            ->withBody($body)
            ->withHeader('content-type', 'application/json')
        ;
    }

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
            $account = $this->getRepository()->get($id);
            $this->setAccount($account);
        }
        return $this->account;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Confirm
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Get account repository
     *
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        if (!$this->repository) {
            $this->setRepository(Orm::getRepository(Account::class));
        }
        return $this->repository;
    }

    /**
     * Set account repository
     *
     * @param RepositoryInterface $repository
     *
     * @return Confirm
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Get E-mail sender
     *
     * @return AccountEmailSenderInterface
     */
    public function getEmailSender()
    {
        if (!$this->emailSender) {
            $this->setEmailSender(
                $this->getContainer()->get('confirmEmailSender')
            );
        }
        return $this->emailSender;
    }

    /**
     * Set e-mail sender
     *
     * @param AccountEmailSenderInterface $emailSender
     *
     * @return Confirm
     */
    public function setEmailSender(AccountEmailSenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
        return $this;
    }

}

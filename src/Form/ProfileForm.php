<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Form;

use Slick\Mvc\Form\EntityForm;
use Slick\Users\Domain\Account;
use Slick\Users\Service\Authentication\AuthenticationAwareInterface;
use Slick\Users\Service\Authentication\AuthenticationAwareMethods;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * ProfileForm
 *
 * @package Slick\Users\Form
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ProfileForm extends EntityForm implements
    ProfileFormInterface,
    AuthenticationAwareInterface,
    DependencyContainerAwareInterface
{

    /**
     * @var Account
     */
    protected $account;

    /**
     * Needed for authentication service usage
     */
    use AuthenticationAwareMethods;

    /**
     * Used to have access to dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Set internal account
     *
     * @param Account $account
     *
     * @return ProfileFormInterface|self|ProfileFormInterface
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
        $this->setData($account->asArray());
        return $this;
    }

    /**
     * Get current editing account
     *
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $this->setAccount($this->getAuthenticationService()->getCurrentAccount());
        }
        return $this->account;
    }
}
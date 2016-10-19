<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Mvc\Controller;
use Slick\Users\Domain\Account;
use Slick\Users\Form\ProfileForm;
use Slick\Users\Form\ProfileFormInterface;
use Slick\Users\Form\UsersForms;

class Profile extends Controller
{

    /**
     * @var ProfileFormInterface|ProfileForm
     */
    protected $profileForm;

    /**
     * @var Account
     */
    protected $account;

    /**
     * Gets profile for property
     *
     * @return ProfileFormInterface|ProfileForm
     */
    public function getProfileForm()
    {
        if (!$this->profileForm){
            $this->setProfileForm(UsersForms::getProfileForm());
        }
        return $this->profileForm;
    }

    /**
     * Sets profile form property
     *
     * @param ProfileFormInterface $profileForm
     *
     * @return Profile
     */
    public function setProfileForm($profileForm)
    {
        $this->profileForm = $profileForm;
        return $this;
    }

    /**
     * Handles the request to edit the profile
     */
    public function index()
    {
        $this->set('profileForm', $this->getProfileForm());
        $this->set('account', $this->getAccount());
        $this->setView('accounts/profile');
    }

    /**
     * Gets account property
     *
     * @return Account
     */
    public function getAccount()
    {
        if (!$this->account) {
            $this->setAccount($this->getProfileForm()->getAccount());
        }
        return $this->account;
    }

    /**
     * Sets account property
     *
     * @param Account $account
     *
     * @return Profile
     */
    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

}

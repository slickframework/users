<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Users\Domain\Account;
use Slick\Users\Form\ProfileForm;
use Slick\Users\Form\ProfileFormInterface;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\ProfileUpdater;
use Slick\Users\Service\Account\ProfileUpdaterInterface;

/**
 * Profile controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Profile extends AccountAwareController
{

    /**
     * @var ProfileFormInterface|ProfileForm
     */
    protected $profileForm;

    /**
     * @var ProfileUpdater
     */
    protected $profileUpdater;

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

        if ($this->getProfileForm()->wasSubmitted()) {
            $this->updateAccount();
        }
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
     * @return ProfileUpdater
     */
    public function getProfileUpdater()
    {
        if (!$this->profileUpdater) {
            /** @var ProfileUpdater $service */
            $service = $this->getContainer()->get('profileUpdater');
            $this->setProfileUpdater($service);
        }
        return $this->profileUpdater;
    }

    /**
     * Set profile updater service
     *
     * @param ProfileUpdater|ProfileUpdaterInterface $profileUpdater
     * @return Profile
     */
    public function setProfileUpdater(ProfileUpdaterInterface $profileUpdater)
    {
        $this->profileUpdater = $profileUpdater;
        return $this;
    }

    /**
     * Updates current submitted changes
     */
    protected function updateAccount()
    {
        if (!$this->getProfileForm()->isValid()) {
            $this->addErrorMessage(
                $this->translate(
                    'Your profile was not updated. Please check the errors below and try again.'
                )
            );
            return;
        }

        $account = $this->getProfileForm()->getAccount();
        $account->hydrate($this->getProfileForm()->getData());
        try {
            $this->getProfileUpdater()->update($account);
            $this->addSuccessMessage(
                $this->translate(
                    'Your profile information was successfully updated.'
                )
            );
        } catch (\Exception $caught) {
            $this->addErrorMessage(
                $caught->getMessage()
            );
            $this->getLogger()->critical($caught->getMessage());
        }

    }

}

<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Users\Exception\Accounts\UnknownEmailException;
use Slick\Users\Form\RecoverPasswordForm;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\RecoverPasswordInterface;
use Slick\Users\Shared\Controller\BaseController;

/**
 * Recover
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Recover extends BaseController
{

    /**
     * @var RecoverPasswordForm
     */
    protected $recoverForm;

    /**
     * @var RecoverPasswordInterface
     */
    protected $recoverService;

    /**
     * Handle recover request password
     */
    public function request()
    {
        $this->setView('accounts/recover');
        $form = $this->getRecoverForm();
        $this->set(compact('form'));

        if ($form->wasSubmitted()) {
            $this->validateForm();
        }
    }

    /**
     * Validates form data
     */
    protected function validateForm()
    {
        if (!$this->getRecoverForm()->isValid()) {
            $this->addErrorMessage(
                $this->translate(
                    'Unable to send recover e-mail. Please check the errors ' .
                    'bellow and try again.'
                )
            );
            return;
        }
        $email = $this->getRecoverForm()->getEmail();
        $this->process($email);
    }

    /**
     * Uses the recover server to sen an e-mail with instructions
     *
     * @param string  $email
     */
    protected function process($email)
    {
        try {
            $this->getRecoverService()
                ->setEmail($email)
                ->requestEmail();
            $this->addSuccessMessage(
                $this->translate(
                    "An e-mail message with password recover instructions " .
                    "was sent to your e-mail address. Please check your " .
                    "inbox to proceed with your password recover."
                )
            );
            $this->getRecoverForm()->get('email')->setValue('');
        } catch (UnknownEmailException $caught) {
            $this->addErrorMessage(
                $this->translate(
                    'We don\'t have any account with this e-mail address.'
                )
            );
            return;

        } catch (\Exception $caught) {
            $message = $this->translate(
                'Error when trying to send recover e-mail: '
                .$caught->getMessage()
            );
            $this->addErrorMessage($message);
            $this->getLogger()
                ->critical(
                    "Error will trying to recover an account password.",
                    ['message' => $message]
                )
            ;
        }
    }

    /**
     * Get the recover password form
     *
     * @return RecoverPasswordForm
     */
    public function getRecoverForm()
    {
        if (!$this->recoverForm) {
            $this->setRecoverForm(UsersForms::getRecoverPasswordForm());
        }
        return $this->recoverForm;
    }

    /**
     * Set the recover password form
     *
     * @param RecoverPasswordForm $recoverForm
     * @return Recover
     */
    public function setRecoverForm(RecoverPasswordForm $recoverForm)
    {
        $this->recoverForm = $recoverForm;
        return $this;
    }

    /**
     * Get the recover service
     *
     * @return RecoverPasswordInterface
     */
    public function getRecoverService()
    {
        if (!$this->recoverService) {
            $this->setRecoverService(
                $this->getContainer()->get('recoverPasswordService')
            );
        }
        return $this->recoverService;
    }

    /**
     * Set recover password service
     *
     * @param RecoverPasswordInterface $recoverService
     *
     * @return Recover
     */
    public function setRecoverService(RecoverPasswordInterface $recoverService)
    {
        $this->recoverService = $recoverService;
        return $this;
    }

}
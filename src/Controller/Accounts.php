<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Mvc\Form\EntityForm;
use Slick\Users\Form\AccountRegisterForm;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\Register;
use Slick\Users\Service\Authentication\AuthenticationAwareInterface;
use Slick\Users\Service\Authentication\AuthenticationAwareMethods;
use Slick\Users\Shared\Controller\BaseController;

/**
 * Users controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Accounts extends BaseController implements
    AuthenticationAwareInterface
{

    /**
     * For authentication data
     */
    use AuthenticationAwareMethods;

    /**
     * @var AccountRegisterForm
     */
    protected $registerForm;

    /**
     * @var Register
     */
    protected $registerService;

    /**
     * Handle sign up registration pages
     */
    public function signUp()
    {
        $form = $this->getRegisterForm();
        $this->checkUser();
        if ($form->wasSubmitted()) {
            try {
                $this->registerAccount($form);
            } catch (\Exception $caught) {
                $message = $this->translate(
                    'Error when trying to register a new account: '
                    .$caught->getMessage()
                );
                $this->addErrorMessage($message);
                $this->getLogger()->error($message);
            }
        }
        $this->set(compact('form'));
    }

    /**
     * Should set redirect if user is not a guest
     */
    public function checkUser()
    {
        if (!$this->getAuthenticationService()->isGuest()) {
            $this->redirect('pages/forbidden');
        }
    }

    /**
     * Calls registration service
     *
     * @param EntityForm $form
     *
     * @return self|$this
     */
    protected function registerAccount(EntityForm $form)
    {
        if (!$form->isValid()) {
            return $this->addErrorMessage(
                $this->translate(
                    'Unable to create new account. Please check the errors ' .
                    'bellow and try again.'
                )
            );
        }
        $request = $this->getRegisterForm()->getData();
        $this->getRegisterService()->execute($request);
        $this->addSuccessMessage(
            $this->translate(
                "Sign up completed successfully."
            )
        );
        return $this->redirect('home');
    }

    /**
     * Gets registerForm property
     *
     * @return AccountRegisterForm
     */
    public function getRegisterForm()
    {
        if (!$this->registerForm) {
            $this->setRegisterForm(UsersForms::getRegisterForm());
        }
        return $this->registerForm;
    }

    /**
     * Sets registerForm property
     *
     * @param EntityForm $registerForm
     *
     * @return Accounts
     */
    public function setRegisterForm(EntityForm $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     * Gets registerService property
     *
     * @return Register
     */
    public function getRegisterService()
    {
        if (!$this->registerService) {
            /** @var Register $service */
            $service = $this->getContainer()->get('accountRegister');
            $this->setRegisterService($service);
        }
        return $this->registerService;
    }

    /**
     * Sets registerService property
     *
     * @param Register $registerService
     *
     * @return Accounts
     */
    public function setRegisterService(Register $registerService)
    {
        $this->registerService = $registerService;
        return $this;
    }

}

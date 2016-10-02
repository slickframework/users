<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\I18n\TranslateMethods;
use Slick\Mvc\Controller;
use Slick\Mvc\Form\EntityForm;
use Slick\Mvc\Http\FlashMessagesMethods;
use Slick\Users\Form\UsersForms;

/**
 * Users controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Accounts extends Controller
{

    use TranslateMethods;

    use FlashMessagesMethods;

    /**
     * @var EntityForm
     */
    protected $registerForm;

    /**
     * Handle sign up registration pages
     */
    public function signUp()
    {
        $form = $this->getRegisterForm();
        if ($form->wasSubmitted()) {
            try {
                $this->registerAccount($form);
            } catch (\Exception $caught) {
                $message = $this->translate(
                    'Error when trying to register a new account: '
                    .$caught->getMessage()
                );
                $this->addErrorMessage($message);
            }
        }
        $this->set(compact('form'));
    }

    /**
     * Calls registration service
     *
     * @param EntityForm $form
     */
    protected function registerAccount(EntityForm $form)
    {
        if (!$form->isValid()) {
            $this->addErrorMessage(
                $this->translate(
                    'Unable to create new account. Please check the errors ' .
                    'bellow and try again.'
                )
            );
            return;
        }
    }

    /**
     * Gets registerForm property
     *
     * @return EntityForm
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

}

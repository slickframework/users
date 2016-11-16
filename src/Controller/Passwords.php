<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Filter\StaticFilter;
use Slick\Orm\Orm;
use Slick\Users\Domain\Repository\TokenRepositoryInterface;
use Slick\Users\Domain\Token;
use Slick\Users\Exception\Accounts\InvalidTokenException;
use Slick\Users\Form\PasswordChangeFormInterface;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\ChangePasswordInterface;
use Slick\Users\Shared\Controller\BaseController;

/**
 * Passwords
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Passwords extends BaseController
{

    /**
     * @var PasswordChangeFormInterface
     */
    protected $form;

    /**
     * @var ChangePasswordInterface
     */
    protected $changePasswordService;

    /**
     * @var Token
     */
    protected $token;

    /**
     * @var TokenRepositoryInterface
     */
    protected $tokenRepository;

    /**
     * Handles password recover change
     */
    public function change()
    {
        $this->setView('accounts/change-password');
        $form = $this->getForm();
        if ($form->wasSubmitted()) {
            $this->changePassword();
        }
        $this->set(compact('form'));
    }

    /**
     * Handle change password on account
     */
    protected function changePassword()
    {
        if (!$this->getForm()->isValid()) {
            $this->addErrorMessage(
                $this->translate(
                    "Password cannot be changed. Please check the errors " .
                    "below and try again."
                )
            );
            return;
        }

        try {
            $this->getChangePasswordService()
                ->setToken($this->getToken())
                ->change($this->getForm()->getPassword());
            $this->addSuccessMessage(
                $this->translate(
                    "Your password was successfully recovered."
                )
            );
            $this->redirect('home');
        } catch (InvalidTokenException $caught) {
            $this->addErrorMessage(
                $this->translate("Invalid change password request.")
            );
            $this->getLogger()->alert(
                "invalid token",
                ['message' => $caught->getMessage()]
            );
        } catch (\Exception $caught) {
            $this->addErrorMessage(
                sprintf(
                    $this->translate("Error while changing password: %s"),
                    $caught->getMessage()
                )
            );
            $this->getLogger()->alert(
                "Error changing password after password recovery",
                ['message' => $caught->getMessage()]
            );
        }
    }

    /**
     * Get the password change form
     *
     * @return PasswordChangeFormInterface
     */
    public function getForm()
    {
        if (!$this->form) {
            $this->setForm(UsersForms::getChangePasswordForm());
        }
        return $this->form;
    }

    /**
     * Set the change password form
     *
     * @param PasswordChangeFormInterface $form
     *
     * @return Passwords
     */
    public function setForm(PasswordChangeFormInterface $form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * Get the change password service
     *
     * @return ChangePasswordInterface
     */
    public function getChangePasswordService()
    {
        if (!$this->changePasswordService) {
            $this->setChangePasswordService(
                $this->getContainer()->get('changePasswordService')
            );
        }
        return $this->changePasswordService;
    }

    /**
     * Set the change password service
     *
     * @param ChangePasswordInterface $changePasswordService
     *
     * @return Passwords
     */
    public function setChangePasswordService(ChangePasswordInterface $changePasswordService)
    {
        $this->changePasswordService = $changePasswordService;
        return $this;
    }

    /**
     * Get token repository
     *
     * @return TokenRepositoryInterface
     */
    public function getTokenRepository()
    {
        if (!$this->tokenRepository) {
            $this->setTokenRepository(Orm::getRepository(Token::class));
        }
        return $this->tokenRepository;
    }

    /**
     * Set token repository
     *
     * @param TokenRepositoryInterface $tokenRepository
     * @return Passwords
     */
    public function setTokenRepository(TokenRepositoryInterface $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
        return $this;
    }

    /**
     * Get token from request
     *
     * @return Token
     */
    public function getToken()
    {
        if (!$this->token) {
            $this->setToken($this->loadToken());
        }
        return $this->token;
    }

    /**
     * Set token
     *
     * @param Token|null $token
     * @return Passwords
     */
    public function setToken(Token $token = null)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get token from request
     *
     * @return null|Token
     */
    protected function loadToken()
    {
        $action = StaticFilter::filter(
            'text',
            $this->request->getQuery('action')
        );
        $tokenStr = StaticFilter::filter(
            'text',
            $this->request->getQuery('token')
        );

        $token = $this->getTokenRepository()->getToken($tokenStr);

        return $action == Token::ACTION_RECOVER && $token ? $token : null;
    }

}

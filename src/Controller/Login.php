<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Slick\Mvc\Http\SessionAwareInterface;
use Slick\Mvc\Http\SessionAwareMethods;
use Slick\Users\Form\LoginForm;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Authentication\AuthenticationAwareInterface;
use Slick\Users\Service\Authentication\AuthenticationAwareMethods;
use Slick\Users\Service\Http\AuthenticationMiddleware;
use Slick\Users\Shared\Controller\BaseController;

/**
 * Login controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Login extends BaseController implements
    SessionAwareInterface,
    AuthenticationAwareInterface
{

    /**
     * @var LoginForm
     */
    protected $loginForm;

    /**
     * @var Authentication
     */
    protected $accountAuthenticationService;

    /**
     * To use session
     */
    use SessionAwareMethods;

    use AuthenticationAwareMethods;

    /**
     * Gets loginForm property
     *
     * @return LoginForm
     */
    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->setLoginForm(UsersForms::getLoginForm());
        }
        return $this->loginForm;
    }

    /**
     * Sets loginForm property
     *
     * @param LoginForm $loginForm
     *
     * @return Login
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    /**
     * Handles sign-in form
     */
    public function signIn()
    {
        $this->checkGuest();
        $this->setView('accounts/sign-in');
        $form = $this->getLoginForm();
        $this->set(compact('form'));

        if ($form->wasSubmitted()) {
            $this->validateAccount();
        }
    }

    public function checkGuest()
    {
        if (!$this->getAuthenticationService()->isGuest()) {
            $this->redirect('sign-out');
        }
    }

    /**
     * Gets authenticationService property
     *
     * @return Authentication
     */
    public function getAccountAuthenticationService()
    {
        if (!$this->accountAuthenticationService) {
            /** @var Authentication $service */
            $service = $this->getContainer()->get('accountAuthentication');
            $this->setAccountAuthenticationService($service);
        }
        return $this->accountAuthenticationService;
    }

    /**
     * Sets authenticationService property
     *
     * @param Authentication $accountAuthenticationService
     *
     * @return Login
     */
    public function setAccountAuthenticationService(
        Authentication $accountAuthenticationService
    ) {
        $this->accountAuthenticationService = $accountAuthenticationService;
        return $this;
    }

    /**
     * Validates user data
     */
    protected function validateAccount()
    {
        $data = $this->getLoginForm()->getData();
        try {
            $valid = $this->getLoginForm()->isValid() &&
                $this->getAccountAuthenticationService()
                    ->login($data['username'], $data['password'], $data['remember']);
            if ($valid) {
                $page = $this->getSessionDriver()
                    ->get(
                        AuthenticationMiddleware::REDIRECT_KEY,
                        $this->getUrl('home')
                    )
                ;
                $page = ltrim($page, '/');
                $this->getSessionDriver()
                    ->erase(AuthenticationMiddleware::REDIRECT_KEY);
                $this->redirect($page);
                return;
            }
        } catch (\Exception $caught) {
            $this->addErrorMessage(
                $this->translate(
                    'Error signing in.'
                )
            );
            $this->getLogger()->alert(
                'Error signing in account.',
                ['error' => $caught->getMessage()]
            );
            return;
        }
        $this->response = $this->getResponse()->withStatus(401);
        $this->addErrorMessage(
            $this->translate(
                'Invalid credentials. Please try again.'
            )
        );
    }
}

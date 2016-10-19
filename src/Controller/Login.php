<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Controller;

use Psr\Log\LoggerInterface;
use Slick\I18n\TranslateMethods;
use Slick\Mvc\Controller;
use Slick\Mvc\Http\FlashMessagesMethods;
use Slick\Mvc\Http\SessionAwareInterface;
use Slick\Mvc\Http\SessionAwareMethods;
use Slick\Users\Form\LoginForm;
use Slick\Users\Form\UsersForms;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Http\AuthenticationMiddleware;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Login controller
 *
 * @package Slick\Users\Controller
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Login extends Controller implements
    DependencyContainerAwareInterface,
    SessionAwareInterface
{

    /**
     * @var LoginForm
     */
    protected $loginForm;

    /**
     * @var Authentication
     */
    protected $authenticationService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * o use dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Needed to display flash messages
     */
    use FlashMessagesMethods;

    /**
     * Needed to translate flash messages
     */
    use TranslateMethods;

    /**
     * To use session
     */
    use SessionAwareMethods;

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
        $this->setView('accounts/sign-in');
        $form = $this->getLoginForm();
        $this->set(compact('form'));

        if ($form->wasSubmitted()) {
            $this->validateAccount();
        }
    }

    /**
     * Gets authenticationService property
     *
     * @return Authentication
     */
    public function getAuthenticationService()
    {
        if (!$this->authenticationService) {
            /** @var Authentication $service */
            $service = $this->getContainer()->get('accountAuthentication');
            $this->setAuthenticationService($service);
        }
        return $this->authenticationService;
    }

    /**
     * Sets authenticationService property
     *
     * @param Authentication $authenticationService
     *
     * @return Login
     */
    public function setAuthenticationService(
        Authentication $authenticationService
    ) {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * Gets logger property
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (!$this->logger) {
            /** @var LoggerInterface $logger */
            $logger = $this->getContainer()->get('logger');
            $this->setLogger($logger);
        }
        return $this->logger;
    }

    /**
     * Sets logger property
     *
     * @param LoggerInterface $logger
     *
     * @return Login
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
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
                $this->getAuthenticationService()
                    ->login($data['username'], $data['password']);
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

        $this->addErrorMessage(
            $this->translate(
                'Invalid credentials. Please try again.'
            )
        );
    }
}

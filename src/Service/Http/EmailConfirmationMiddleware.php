<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\PhpEnvironment\Request;
use Slick\Http\Server\AbstractMiddleware;
use Slick\Http\Server\MiddlewareInterface;
use Slick\I18n\TranslateMethods;
use Slick\Mvc\Http\FlashMessagesMethods;
use Slick\Orm\Orm;
use Slick\Users\Domain\Repository\TokenRepository;
use Slick\Users\Domain\Token;
use Slick\Users\Service\Account\Authentication;
use Slick\Users\Service\Account\Authentication\SignedInAccount;
use Slick\Users\Service\Authentication\AuthenticationAwareInterface;
use Slick\Users\Service\Authentication\AuthenticationAwareMethods;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Email Confirmation Middleware
 *
 * @package Slick\Users\Service\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class EmailConfirmationMiddleware extends AbstractMiddleware implements
    MiddlewareInterface,
    AuthenticationAwareInterface,
    DependencyContainerAwareInterface
{
    /**
     * @var TokenRepository
     */
    protected $repository;

    /**
     * @var Token
     */
    protected $token;

    /**
     * @var ServerRequestInterface|Request
     */
    protected $request;

    use FlashMessagesMethods;

    use TranslateMethods;

    use AuthenticationAwareMethods;

    use DependencyContainerAwareMethods;

    /**
     * Handles a Request and updated the response
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function handle(
        ServerRequestInterface $request, ResponseInterface $response
    )
    {
        $this->request = $request;
        if ($token = $this->getToken()) {
            $this->validateToken($token);
            $token->delete();
        }
        return $this->executeNext($request, $response);
    }

    /**
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
     * @param Token $token
     * @return EmailConfirmationMiddleware
     */
    public function setToken($token = null)
    {
        $this->token = $token;
        return $this;
    }


    /**
     * @return TokenRepository
     */
    public function getRepository()
    {
        if (!$this->repository) {
            $this->setRepository(Orm::getRepository(Token::class));
        }
        return $this->repository;
    }

    /**
     * @param TokenRepository $repository
     * @return EmailConfirmationMiddleware
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Loads token
     *
     * @return null|Token
     */
    protected function loadToken()
    {
        $token = null;
        $tokenStr = $this->request->getQuery('token', false);
        $action = $this->request->getQuery('action', false);
        if ($tokenStr && $action) {
            $tkn = $this->getRepository()->getToken($tokenStr);
            $token = !$tkn->hasExpired() && $tkn->action == $action
                ? $tkn
                : null;
        }
        return $token;
    }

    /**
     * Check token validity
     *
     * If the selector is present but the token does not match, a theft is
     * assumed. The user receives a strongly worded warning and all of the
     * user's tokens will be deleted.
     *
     * @param Token $token
     */
    protected function validateToken(Token $token)
    {
        if (!$token->isValid()) {
            $this->getRepository()->deleteAccountTokens($token);
            $this->addErrorMessage(
                $this->translate(
                    "Possible token theft detected. For your account data " .
                    "security all account generated tokens were delete."
                )
            );
            return;
        }
        $this->confirmAccount($token);
    }

    /**
     * Set flag confirmed in the account
     *
     * @param Token $token
     */
    protected function confirmAccount(Token $token)
    {
        $token->account->confirmed = true;
        $token->account->save();
        $this->addSuccessMessage(
            $this->translate("Your e-mail address was successfully confirmed.")
        );
        if (
            $this->getAuthenticationService()->getCurrentAccount()->getId()
            == $token->account->getId()
        ) {
            $this->getAuthenticationService()
                ->getCurrentAccount()
                ->confirmed = true;
            /** @var SignedInAccount $accData */
            $accData = $this->getAuthenticationService()
                ->getSession()
                ->get(Authentication::SESSION_KEY);
            $accData->account = $token->account;
            $this->getAuthenticationService()
                ->getSession()
                ->set(Authentication::SESSION_KEY, $accData);
        }

    }

}

<?php

/**
 * This file is part of Users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account;

use League\Event\EmitterAwareInterface;
use League\Event\EmitterInterface;
use Psr\Log\LoggerInterface;
use Slick\Users\Shared\Common\LoggerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Account Service
 *
 * @package Slick\Users\Service\Account
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
abstract class AccountService implements
    EmitterAwareInterface ,
    DependencyContainerAwareInterface
{

    /**
     * @var AccountEventEmitter|EmitterInterface
     */
    protected $emitter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Needed to use dependency container
     */
    use DependencyContainerAwareMethods;

    /**
     * Register
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Set the Emitter.
     *
     * @param EmitterInterface $emitter
     *
     * @return $this
     */
    public function setEmitter(EmitterInterface $emitter = null)
    {
        $this->emitter = $emitter;
        return $this;
    }

    /**
     * Get the Emitter.
     *
     * @return EmitterInterface|AccountEventEmitter
     */
    public function getEmitter()
    {
        if (!$this->emitter) {
            /** @var AccountEventEmitter $emitter */
            $emitter = $this->getContainer()->get('accountEventEmitter');
            $this->setEmitter($emitter);
        }
        return $this->emitter;
    }
}

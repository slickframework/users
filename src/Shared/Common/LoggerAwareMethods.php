<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Common;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Logger Aware Methods
 *
 * Logger interface implementation methods
 *
 * @package Slick\Users\Shared\Common
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait LoggerAwareMethods
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Set logger service
     *
     * @param LoggerInterface $logger
     *
     * @return self|$this|LoggerAwareInterface
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Get logger service
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (!$this->logger) {
            $logger = $this->getContainer()->get('logger');
            $this->setLogger($logger);
        }
        return $this->logger;
    }

    /**
     * Get application dependency container
     *
     * @return ContainerInterface
     */
    abstract public function getContainer();
}

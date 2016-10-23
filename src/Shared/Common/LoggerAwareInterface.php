<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Common;

use Psr\Log\LoggerInterface;

/**
 * Logger Aware Interface
 *
 * @package Slick\Users\Shared\Common
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface LoggerAwareInterface
{

    /**
     * Set logger service
     *
     * @param LoggerInterface $logger
     *
     * @return self|$this|LoggerAwareInterface
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Get logger service
     *
     * @return LoggerInterface
     */
    public function getLogger();
}

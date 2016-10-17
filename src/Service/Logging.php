<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service;

use Monolog\Handler\HandlerInterface;
use Slick\Common\Log;
use Slick\Users\Shared\Configuration\SettingsAwareInterface;
use Slick\Users\Shared\Configuration\SettingsAwareMethods;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * Logging
 *
 * @package Slick\Users\Service
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Logging extends Log implements
    DependencyContainerAwareInterface,
    SettingsAwareInterface
{
    /**
     * For handler retrieval
     */
    use DependencyContainerAwareMethods;

    /**
     * For settings retrieval
     */
    use SettingsAwareMethods;

    /**
     * Adds a monolog handler to the handlers stack
     *
     * @param HandlerInterface $handler
     *
     * @return $this
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
        return $this;
    }

    /**
     * Gets the logger for the channel with the provided name.
     *
     * @param string $name The loggers channel name to retrieve.
     *
     * @return \Monolog\Logger The logger object for the given channel name.
     */
    public function getLogger($name = null)
    {
        /** @var HandlerInterface $default */
        $default = $this->getContainer()->get('defaultHandler');
        $this->addHandler($default);
        self::$defaultLogger = $this->getSettings()
            ->get('logging.facility', 'slick-users');
        return parent::getLogger($name);
    }
}

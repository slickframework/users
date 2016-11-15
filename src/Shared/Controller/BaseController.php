<?php

/**
 * This file is part of slick/users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Controller;

use Psr\Log\LoggerInterface;
use Slick\I18n\TranslateMethods;
use Slick\Mvc\Controller;
use Slick\Mvc\Http\FlashMessagesMethods;
use Slick\Users\Shared\Common\LoggerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareInterface;
use Slick\Users\Shared\Di\DependencyContainerAwareMethods;

/**
 * BaseController
 *
 * @package Slick\Users\Shared
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class BaseController extends Controller implements
    DependencyContainerAwareInterface,
    LoggerAwareInterface
{

    /**
     * To translate session messages
     */
    use TranslateMethods;

    /**
     * For session message display
     */
    use FlashMessagesMethods;

    /**
     * For dependency container aware interface implementation
     */
    use DependencyContainerAwareMethods;

    /**
     * @var LoggerInterface
     */
    protected $logger;

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
     * @return self|$this|BaseController
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }
}

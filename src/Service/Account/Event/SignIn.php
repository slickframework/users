<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Event;

use League\Event\AbstractEvent;
use League\Event\EventInterface;
use Slick\Users\Domain\Account;

/**
 * Sign In event
 *
 * @package Slick\Users\Service\Account\Event
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SignIn extends AccountEvent implements EventInterface
{
    const NAME = 'sign-in';
}

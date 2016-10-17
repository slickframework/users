<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Event;

use League\Event\EventInterface;

/**
 * Sign Up event
 *
 * @package Slick\Users\Service\Account\Event
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SignUp extends SignIn implements EventInterface
{

    const NAME = 'sign-up';
}

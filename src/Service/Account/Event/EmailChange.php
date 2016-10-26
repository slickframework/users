<?php

/**
 * This file is part of Users.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Event;

use League\Event\EventInterface;

/**
 * Account E-mail Change event
 *
 * @package Slick\Users\Service\Account\Event
 * @author  Filipe Silva <filipe.silva@sata.pt>
 */
class EmailChange extends AccountEvent implements EventInterface
{
    const NAME = 'email-change';
}

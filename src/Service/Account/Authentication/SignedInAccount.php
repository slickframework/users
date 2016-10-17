<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Service\Account\Authentication;

use Slick\Common\Base;
use Slick\Users\Domain\Account;

/**
 * Signed In Account
 *
 * This object is used to store the signed in account data.
 *
 * @package Slick\Users\Service\Account\Authentication
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property Account $account
 * @property bool    $signedIn
 * @property bool    $dirty
 * @property bool    $guest
 *
 * @method bool isSignedIn()
 * @method bool isGuest()
 * @method bool isDirty()
 */
class SignedInAccount extends Base
{

    /**
     * @readwrite
     * @var Account
     */
    protected $account;

    /**
     * @readwrite
     * @var bool
     */
    protected $signedIn = true;

    /**
     * @readwrite
     * @var bool
     */
    protected $guest = false;

    /**
     * @readwrite
     * @var bool
     */
    protected $dirty = false;
}

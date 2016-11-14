<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Exception\Accounts;

use InvalidArgumentException;
use Slick\Users\Exception;

/**
 * Invalid Token Exception
 *
 * @package Slick\Users\Exception\Accounts
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class InvalidTokenException extends InvalidArgumentException implements Exception
{

}

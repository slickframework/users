<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Exception\Accounts;

use RuntimeException;
use Slick\Users\Exception;

/**
 * Unknown Email Exception
 *
 * Search for an account with a given e-mail address fails
 *
 * @package Slick\Users\Exception\Accounts
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UnknownEmailException extends RuntimeException implements Exception
{

}

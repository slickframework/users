<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Filter;

use Slick\Filter\Exception;
use Slick\Filter\FilterInterface;

/**
 * Email address filter
 *
 * @package Slick\Users\Shared\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Email implements FilterInterface
{

    /**
     * Returns the result of filtering $value
     *
     * @param mixed $value
     *
     * @throws Exception\CannotFilterValueException
     *      If filtering $value is impossible.
     *
     * @return mixed
     */
    public function filter($value)
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }
}

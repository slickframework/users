<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\Utility;

use Slick\Users\Shared\DataType\DateTime;

/**
 * Current Date Aware Interface
 *
 * @package Slick\Users\Shared\Utility
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface CurrentDateAwareInterface
{

    /**
     * Returns current datetime object
     *
     * @return DateTime
     */
    public function getCurrentDate();

    /**
     * Set current datetime object
     *
     * @param DateTime $date
     *
     * @return self|$this|CurrentDateAwareInterface
     */
    public function setCurrentDate(DateTime $date);
}

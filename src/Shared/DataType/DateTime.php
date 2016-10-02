<?php

/**
 * This file is part of slick/users package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Users\Shared\DataType;

use DateTime as PhpDateTime;
use DateTimeZone;

/**
 * DateTime
 *
 * @package Slick\Users\Shared\DataType
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class DateTime extends PhpDateTime
{

    const DEFAULT_TIMEZONE = 'UTC';
    const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    /**
     * DateTime
     *
     * @param string $time
     * @param DateTimeZone|null $timezone
     */
    public function __construct($time = 'now', DateTimeZone $timezone = null)
    {
        parent::__construct(
            $this->evaluateTime($time),
            $this->evaluateTimeZone($timezone)
        );
    }

    /**
     * Returns the date as a string using the default format constant
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format(self::DEFAULT_FORMAT);
    }

    /**
     * Check constructor timezone parameter to determine this date tie zone
     *
     * @param DateTimeZone|null $timezone
     *
     * @return DateTimeZone
     */
    protected function evaluateTimeZone(DateTimeZone $timezone = null)
    {
        $timezone = (!$timezone)
            ? new DateTimeZone(self::DEFAULT_TIMEZONE)
            : $timezone;
        return $timezone;
    }

    /**
     * Check if constructor time parameter is a DateTime object
     *
     * @param mixed $time
     *
     * @return string
     */
    protected function evaluateTime($time)
    {
        if ($time instanceof PhpDateTime) {
            return "@{$time->getTimestamp()}";
        }
        return $this->timeAsTimestamp($time);
    }

    /**
     * Check if constructor time parameter is a timestamp integer
     *
     * @param mixed $time
     *
     * @return string
     */
    protected function timeAsTimestamp($time)
    {
        $value = $time;
        if (is_integer($time)) {
            $value = "@{$time}";
        }
        return $value;
    }
}

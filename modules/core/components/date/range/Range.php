<?php

namespace app\modules\core\components\date\range;

use yii\base\InvalidArgumentException;

class Range
{
    public static function getAvailableInterfaces() : array
    {
        return [
            // basic
            DateRangeInterface::INTERFACE_HOUR, DateRangeInterface::INTERFACE_DAY,
            DateRangeInterface::INTERFACE_WEEK, DateRangeInterface::INTERFACE_MONTH,

            // advanced
            DateRangeInterface::INTERFACE_YESTERDAY, DateRangeInterface::INTERFACE_LAST_WEEK,
            DateRangeInterface::INTERFACE_LAST_MONTH,
        ];
    }

    public static function compareTimeInterface(string $checkingInterface, string $interface) : bool
    {
        if (empty($checkingInterface)) {
            return false;
        }

        if (!in_array($interface, self::getAvailableInterfaces())) {
            throw new InvalidArgumentException('Не поддерживаемый интерфейс времени');
        }

        return trim(strtolower($checkingInterface)) === $interface;
    }

    /**
     * @param string $by
     * @return AbstractRange
     * @throws \yii\base\InvalidConfigException
     */
    public static function supportInterfaceBy(string $by) : AbstractRange
    {
        if (!in_array($by, self::getAvailableInterfaces())) {
            throw new InvalidArgumentException('Недопустимый интервал времени');
        }

        if (self::isHour($by)) {
            return new Hour();
        }

        if (self::isDay($by)) {
            return new Day($by);
        }

        if (self::isWeek($by)) {
            return new Week($by);
        }

        if (self::isMonth($by)) {
            return new Month($by);
        }
    }

    public static function isHour($interface) : bool
    {
        return in_array($interface, [DateRangeInterface::INTERFACE_HOUR]);
    }

    public static function isDay($interface) : bool
    {
        return in_array($interface, [DateRangeInterface::INTERFACE_DAY, DateRangeInterface::INTERFACE_YESTERDAY]);
    }

    public static function isWeek($interface) : bool
    {
        return in_array($interface, [DateRangeInterface::INTERFACE_WEEK, DateRangeInterface::INTERFACE_LAST_WEEK]);
    }

    public static function isMonth($interface) : bool
    {
        return in_array($interface, [DateRangeInterface::INTERFACE_MONTH, DateRangeInterface::INTERFACE_LAST_MONTH]);
    }

    public static function isYear($interface) : bool {}
}
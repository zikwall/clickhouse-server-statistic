<?php

namespace app\modules\rest\helpers;

use app\modules\rest\helpers\time\AbstractTime;
use app\modules\rest\helpers\time\Day;
use app\modules\rest\helpers\time\Hour;
use app\modules\rest\helpers\time\Month;
use app\modules\rest\helpers\time\Week;
use yii\base\InvalidArgumentException;

class DateHelper
{
    const INTERFACE_HOUR = 'hour';
    const INTERFACE_DAY = 'day';
    const INTERFACE_WEEK = 'week';
    const INTERFACE_MONTH = 'month';

    public static function getAvailableInterfaces() : array
    {
        return [
            self::INTERFACE_HOUR, self::INTERFACE_DAY, self::INTERFACE_WEEK, self::INTERFACE_MONTH
        ];
    }

    public static function getStartOfDay() : int
    {
        return mktime(0, 0, 0);
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

    public static function supportInterfaceBy(string $by) : AbstractTime
    {
        $availableBys = ['hour', 'day', 'week', 'month'];

        if (!in_array($by, $availableBys)) {
            throw new InvalidArgumentException('Недопустимый интервал времени');
        }

        switch ($by) {
            case 'hour':
                return new Hour();
            case 'day':
                return new Day();
            case 'week':
                return new Week();
            case 'month':
                return new Month();
            default:
                return new Day();
        }
    }
}
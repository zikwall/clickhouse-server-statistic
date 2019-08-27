<?php

namespace app\modules\core\components\date\range;

class DateRangeInterface
{
    const INTERFACE_HOUR = 'hour';
    const INTERFACE_DAY = 'day';
    const INTERFACE_WEEK = 'week';
    const INTERFACE_MONTH = 'month';
    const INTERFACE_YEAR = 'year';

    const INTERFACE_YESTERDAY = 'yesterday';
    const INTERFACE_QUARTER = 'month-quarter';
    const INTERFACE_HALF_YEAR = 'half-year';

    const INTERFACE_LAST_WEEK = 'last-week';
    const INTERFACE_LAST_MONTH = 'last-month';
    const INTERFACE_LAST_QUARTER = 'last-month-quarter';
    const INTERFACE_LAST_HALF_YEAR = 'last-half-year';
}
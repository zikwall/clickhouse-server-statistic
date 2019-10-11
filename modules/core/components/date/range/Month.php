<?php

namespace app\modules\core\components\date\range;

class Month extends AbstractRange
{
    public static $months = [
        'январь'    => '01',
        'февраль'   => '02',
        'март'      => '03',
        'апрель'    => '04',
        'май'       => '05',
        'июнь'      => '06',
        'июль'      => '07',
        'август'    => '08',
        'сентябрь'  => '09',
        'октябрь'   => '10',
        'ноябрь'    => '11',
        'декабрь'   => '12',
    ];

    public function __construct($by)
    {
        parent::__construct($by);
    }

    protected function init(): void
    {
        $this->resolveInterfaces = [
            DateRangeInterface::INTERFACE_MONTH => function () : void {
                $this->current();
            },
            DateRangeInterface::INTERFACE_LAST_MONTH => function () : void {
                $this->last();
            },
            DateRangeInterface::INTERFACE_QUARTER => function () : void {

            },
            DateRangeInterface::INTERFACE_LAST_QUARTER => function () : void {

            }
        ];
    }

    protected function current() : void
    {
        $this->timestamp = strtotime(date('Y-m-01 00:00:00'));
        $this->endTimestamp = Day::endOfCurrent();
    }

    protected function last() : void
    {
        $this->timestamp = strtotime("first day of previous month");
        $this->endTimestamp = strtotime("last day of previous month");
    }

    public static function NameToNumber(string $monthName) : string
    {
        if (!isset(self::$months[$monthName])) {
            throw new \InvalidArgumentException('Совсем дурак чтоле, в месяцах запулатся, уважаемый?');
        }

        return self::$months[$monthName];
    }
}
<?php

namespace app\modules\core\components\date\range;

class Month extends AbstractRange
{
    public static $months = [
        'Январь'    => '01',
        'Февраль'   => '02',
        'Март'      => '03',
        'Апрель'    => '04',
        'Май'       => '05',
        'Июнь'      => '06',
        'Июль'      => '07',
        'Август'    => '08',
        'Сеньтябрь' => '09',
        'Отрябрь'   => '10',
        'Ноябрь'    => '11',
        'Декабрь'   => '12',
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
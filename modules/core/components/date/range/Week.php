<?php

namespace app\modules\core\components\date\range;

class Week extends AbstractRange
{
    public function __construct($by)
    {
        parent::__construct($by);
    }

    protected function init() : void
    {
        $this->resolveInterfaces = [
            DateRangeInterface::INTERFACE_WEEK => function () : void {
                $this->current();
            },
            DateRangeInterface::INTERFACE_LAST_WEEK => function () : void {
                $this->last();
            }
        ];
    }

    protected function current() : void
    {
        $this->timestamp = mktime(0, 0, 0, date("n"), date("j") - date("N") + 1);
        $this->endTimestamp = Day::endOfCurrent();
    }

    protected function last() : void
    {
        $this->timestamp = static::firstDayOfLast();
        $this->endTimestamp = static::lastDayOfLast();
    }

    public static function firstDayOfLast() : int
    {
        return mktime(0,0,0, date("m"),date("d") - date("w") - 6);
    }

    public static function lastDayOfLast() : int
    {
        return mktime(0,0,0, date("m"),date("d") - date("w"));
    }
}
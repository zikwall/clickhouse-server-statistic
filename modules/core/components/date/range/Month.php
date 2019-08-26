<?php

namespace app\modules\core\components\date\range;

class Month extends AbstractRange
{
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
}
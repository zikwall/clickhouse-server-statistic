<?php

namespace app\modules\core\components\date\range;

class Day extends AbstractRange
{
    public function __construct($by)
    {
        parent::__construct($by);
    }

    protected function init() : void
    {
        $this->resolveInterfaces = [
            DateRangeInterface::INTERFACE_DAY => function () : void {
                $this->current();
            },
            DateRangeInterface::INTERFACE_YESTERDAY => function () : void {
                $this->yesterday();
            }
        ];
    }

    protected function current() : void
    {
        $this->timestamp = static::beginOfCurrent();
        $this->endTimestamp = static::endOfCurrent();
    }

    protected function yesterday() : void
    {
        $this->timestamp = static::beginOfLast();
        $this->endTimestamp = static::endOfLast();
    }

    public static function beginOfCurrent() : int
    {
        return mktime(0, 0, 0);
    }

    public static function endOfCurrent() : int
    {
        return mktime(23, 59, 59);
    }

    public static function beginOfLast() : int
    {
        return strtotime('-1 day', static::beginOfCurrent());
    }

    public static function endOfLast() : int
    {
        return strtotime('-1 day', static::endOfCurrent());
    }
}
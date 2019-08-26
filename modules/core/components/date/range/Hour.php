<?php

namespace app\modules\core\components\date\range;

class Hour extends AbstractRange
{
    public function __construct()
    {
        $this->timestamp = mktime(date('H'));
    }

    protected function init(): void
    {
        // TODO: Implement init() method.
    }
}
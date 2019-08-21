<?php

namespace app\modules\rest\helpers\time;

use app\modules\rest\helpers\DateHelper;

class Day extends AbstractTime
{
    public function __construct()
    {
        $this->timestamp = DateHelper::getStartOfDay();
    }
}
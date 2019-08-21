<?php

namespace app\modules\rest\helpers\time;

class Month extends AbstractTime
{
    public function __construct()
    {
        $this->timestamp = strtotime(date('Y-m-01 00:00:00'));
    }
}
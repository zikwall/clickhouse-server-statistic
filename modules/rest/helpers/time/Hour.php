<?php

namespace app\modules\rest\helpers\time;

class Hour extends AbstractTime
{
    public function __construct()
    {
        $this->timestamp = mktime(date('H'));
    }
}
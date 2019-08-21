<?php

namespace app\modules\rest\helpers\time;

class Week extends AbstractTime
{
    public function __construct()
    {
        $this->timestamp = mktime(0, 0, 0, date("n"), date("j") - date("N") + 1);
    }
}
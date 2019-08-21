<?php

namespace app\modules\rest\helpers\time;

abstract class AbstractTime
{
    protected $timestamp = 0;

    final public function getTimestamp() : int
    {
        return $this->timestamp - 3600 * 3;
    }
}
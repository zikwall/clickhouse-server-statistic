<?php

namespace app\modules\clickhouse\components;

use Tinderbox\Clickhouse\Client;
use Tinderbox\ClickhouseBuilder\Query\Builder;

trait ConnectionTrait
{
    /**
     * @return Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
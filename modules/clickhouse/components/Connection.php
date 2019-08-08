<?php

namespace app\modules\clickhouse\components;

use Tinderbox\Clickhouse\Server;
use Tinderbox\Clickhouse\ServerProvider;
use Tinderbox\Clickhouse\Client;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 *
 * @docs for CH query builder @see https://github.com/the-tinderbox/ClickhouseBuilder
 * @docs for CH client @see https://github.com/the-tinderbox/ClickhouseClient
 */
class Connection extends Component
{
    use ConnectionTrait;

    public $host;
    public $port;
    public $user;
    public $pass;
    public $db;

    /**
     * @var Client
     */
    public $client;

    /**
     * @var Builder
     */
    public $builder;

    public function init()
    {
        $serverProvider = (new ServerProvider())->addServer($this->createServer());
        $this->client = new Client($serverProvider);
        $this->builder = new Builder($this->client);

        return parent::init();
    }

    final protected function checkConnectionOptions() : bool
    {
        return empty($this->user) && empty($this->pass);
    }

    protected function createServer() : Server
    {
        return new Server(
            $this->host,
            $this->port,
            $this->db,
            $this->user,
            $this->pass
        );
    }
}
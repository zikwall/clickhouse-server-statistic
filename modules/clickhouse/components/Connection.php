<?php

namespace app\modules\clickhouse\components;

use Tinderbox\Clickhouse\Server;
use Tinderbox\Clickhouse\ServerProvider;
use Tinderbox\Clickhouse\Client;
use Tinderbox\ClickhouseBuilder\Query\Builder;
use yii\base\InvalidConfigException;

/**
 *
 * @docs for CH query builder @see https://github.com/the-tinderbox/ClickhouseBuilder
 * @docs for CH client @see https://github.com/the-tinderbox/ClickhouseClient
 */
class Connection
{
    use ConnectionTrait;

    public $host = 'localhost';
    public $port = '8123';
    public $user = 'default';
    public $pass;
    public $db = 'default';

    /**
     * @var Client
     */
    public $client;

    /**
     * @var Builder
     */
    public $builder;

    public function __construct()
    {
        /*if (!$this->checkConnectionOptions()) {
            throw new InvalidConfigException('Пожалуйста, укажите правильную конфигурацию!');
        }*/

        $serverProvider = (new ServerProvider())->addServer($this->createServer());
        $this->client = new Client($serverProvider);
        $this->builder = new Builder($this->client);
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
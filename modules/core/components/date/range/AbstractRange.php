<?php

namespace app\modules\core\components\date\range;

use yii\base\InvalidArgumentException;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;

abstract class AbstractRange extends DateRangeInterface
{
    protected $timestamp = 0;
    protected $endTimestamp = 0;
    protected $resolveInterfaces = [];

    /**
     * AbstractTime constructor.
     * @param string $interface
     * @throws InvalidConfigException
     */
    public function __construct(string $interface)
    {
        $this->init();
        $this->resolveTime($interface);
    }

    protected abstract function init() : void;

    final public function getTimestamp() : int
    {
        return $this->timestamp - 3600 * 3;
    }

    final public function getEndTimestamp()
    {
        return $this->endTimestamp - 3600 * 3;
    }

    /**
     * @param string $interface
     * @throws InvalidConfigException
     */
    final protected function resolveTime(string $interface) : void
    {
        if (empty($this->resolveInterfaces)) {
            throw new InvalidConfigException('Не могу определить временной интервал.');
        }

        if (!in_array($interface, array_keys($this->resolveInterfaces))) {
            throw new InvalidArgumentException('Не поддерживаемый временной интервал.');
        }

        if (!is_callable($this->resolveInterfaces[$interface])) {
            throw new InvalidCallException('Не могу, я просто не могу инициализировать интрефейс времени, ну не получается :(');
        }

        $this->resolveInterfaces[$interface]();
    }
}
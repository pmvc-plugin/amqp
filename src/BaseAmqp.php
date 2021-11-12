<?php

namespace PMVC\PlugIn\amqp;

use PMVC\HashMap;
use PhpAmqpLib\Message\AMQPMessage;

class BaseAmqp extends HashMap
{
    /**
     * Group ID
     */
    protected $modelId;

    /**
     * Amqp instance
     */
    public $engine;

    /**
     * Construct
     */
    public function __construct($engine, $modelId=null)
    {
        $this->engine = $engine;
        $this->modelId = $modelId;
        $engine->queue_declare($modelId, false, true, false, false);
    }

    public function offsetSet($k, $v)
    {
        $json = json_encode($v);
        $msg = new AMQPMessage($json, [
          'delivery_mode' => AMQPMessage :: DELIVERY_MODE_PERSISTENT
        ]);
        $this->engine->basic_publish($msg, '', $this->modelId);
    }

    public function &offsetGet($callback = NULL)
    {
        $engine = $this->engine;
        $result = $engine->basic_get($this->modelId, true);
        return \PMVC\fromJson(\PMVC\get($result, 'body', null), true);
    }
}

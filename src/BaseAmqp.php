<?php

namespace PMVC\PlugIn\amqp;

use PMVC\HashMap;
use PhpAmqpLib\Message\AMQPMessage;

class BaseAmqp extends HashMap
{
    /**
     * Group ID
     */
    protected $groupId;
    /**
     * Amqp instance
     */
    public $db;

    /**
     * Construct
     */
    public function __construct($db, $groupId=null)
    {
        $this->db = $db;
        $this->groupId = $groupId;
        $db->queue_declare($groupId, false, true, false, false);
    }

    public function offsetSet($k, $v)
    {
        $json = json_encode($v);
        $msg = new AMQPMessage($json, [
          'delivery_mode' => AMQPMessage :: DELIVERY_MODE_PERSISTENT
        ]);
        $this->db->basic_publish($msg, '', $this->groupId);
    }

    public function &offsetGet($callback = NULL)
    {
        $db = $this->db;
        $result = $db->basic_get($this->groupId, true);
        return \PMVC\fromJson(\PMVC\get($result, 'body', null), true);
    }
}

<?php

namespace PMVC\PlugIn\amqp;

use PMVC\PlugIn;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use IdOfThings\GetDb;

\PMVC\initPlugin(['guid'=>null]);
\PMVC\l(__DIR__.'/src/BaseAmqp.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\amqp';

class amqp extends GetDb 
{
    private $_ch;
    private $_conn;

    public function init()
    {
      $defaultArr = [
        'port'=> 5672,
        'user'=> 'guest',
        'pass'=> 'guest' 
      ];
      foreach ($defaultArr as $k=>$v) {
        if (!isset($this[$k])) {
          $this[$k] = $v;
        }
      }
      if ($this['host']) {
        $this->initAmqp();
      }
    }

    public function initAmqp()
    {
      if (empty($this['host'])) {
        return !trigger_error('not set amqp host', E_USER_WARNING);
      }
      $connection = new AMQPStreamConnection($this['host'], $this['port'], $this['user'], $this['pass']);
      $channel = $connection->channel();
      $this->_conn = $connection;
      if ($channel) {
        $channel->basic_qos(null, 1, null);
        $this->setDefaultAlias($channel);
        $this->setConnected(true);
        $this->_ch = $channel;
      }
    }

    public function getChannel()
    {
      return $this->_ch;
    }

    public function __destruct()
    {
      if ($this->_ch) {
        $this->_ch->close();
        $this->_conn->close();
      }
    }

    public function getBaseDb()
    {
        return __NAMESPACE__.'\BaseAmqp';
    }

    public function getNameSpace()
    {
        return __NAMESPACE__;
    }
  
}

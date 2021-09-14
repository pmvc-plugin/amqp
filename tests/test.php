<?php
namespace PMVC\PlugIn\amqp;

use PMVC\TestCase;

class AmqpTest extends TestCase
{
    private $_plug = 'amqp';

    private $_data = ['a' => 'b', 'c' => 'd'];

    public function pmvc_setup()
    {
        \PMVC\unplug($this->_plug);
    }

    public function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->haveString($this->_plug, $output);
    }

    /**
     * @expectedException DomainException
     */
    public function testInitAmqp()
    {
        $this->willThrow(function () {
            $p = \PMVC\plug($this->_plug);
            $hello = $p->getDb('hello');
            $hello[] = $this->_data;
        }, false);
    }

    /**
     * @expectedException DomainException
     */
    public function testGetAmqp()
    {
        $this->willThrow(function () {
            $p = \PMVC\plug($this->_plug);
            $hello = $p->getDb('hello');
            var_dump($hello[null]);
        }, false);
    }
}

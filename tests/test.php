<?php
namespace PMVC\PlugIn\amqp;

use PHPUnit_Framework_TestCase;

class AmqpTest extends PHPUnit_Framework_TestCase
{
    private $_plug = 'amqp';

    private $_data = ['a'=>'b', 'c'=>'d'];

    public function setup()
    {
      \PMVC\unplug($this->_plug);
    }

    public function testPlugin()
    {
        ob_start();
        print_r(\PMVC\plug($this->_plug));
        $output = ob_get_contents();
        ob_end_clean();
        $this->assertContains($this->_plug,$output);
    }

    /**
     * @expectedException DomainException 
     */
    public function testInitAmqp()
    {
        $p = \PMVC\plug($this->_plug);
        $hello = $p->getDb('hello');
        $hello[]=$this->_data;
    }

    /**
     * @expectedException DomainException
     */
    public function testGetAmqp()
    {
        $p = \PMVC\plug($this->_plug);
        $hello = $p->getDb('hello');
        var_dump($hello[null]);
    }
}

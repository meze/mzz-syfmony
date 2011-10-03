<?php

namespace Mzz\MzzBundle\Tests\Acl;
use Mzz\MzzBundle\Acl\Loaders\Config;


class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getsOptionForControllerAndMethodFromReader()
    {
        $reader = $this->getMock('Mzz\MzzBundle\Acl\Loaders\IConfigReader');

        $reader
            ->expects($this->once())
            ->method('getOptionForControllerAndMethod')
            ->with($this->equalTo('test'), $this->equalTo('class'), $this->equalTo('method'))
            ->will($this->returnValue(true));

        $config = new Config($reader);
        $this->assertTrue($config->getOptionForControllerAndMethod('test', 'class', 'method'));
    }


    /**
     * @test
     */
    public function getsOptionForControllerFromReader()
    {
        $reader = $this->getMock('Mzz\MzzBundle\Acl\Loaders\IConfigReader');

        $reader
            ->expects($this->once())
            ->method('getOptionForController')
            ->with($this->equalTo('test'), $this->equalTo('class'))
            ->will($this->returnValue(true));

        $config = new Config($reader);
        $this->assertTrue($config->getOptionForController('test', 'class'));
    }

}

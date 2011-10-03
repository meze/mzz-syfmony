<?php

namespace Mzz\MzzBundle\Tests\Acl;

use Mzz\MzzBundle\Acl\Loaders\XmlConfigReader;

class XmlConfigReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function throwsExceptionIfFileIsNotFound()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/_not_exists_');
    }

    /**
     * @test
     */
    public function shouldReturnValuesForMethodsOfControllers()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl.xml');

        $this->assertEquals(array('USER', 'ADMIN'), $loader->getOptionForControllerAndMethod('roles', 'testController', 'index'));
        $this->assertEquals(array('ADMIN'), $loader->getOptionForControllerAndMethod('roles', 'testController', 'admin'));
        $this->assertEquals(array('ADMIN'), $loader->getOptionForControllerAndMethod('roles', 'welcomeController', 'goodbye'));
        $this->assertEquals(array('ANONYMOUS'), $loader->getOptionForControllerAndMethod('roles', 'welcomeController', 'index'));
    }

    /**
     * @test
     */
    public function shouldReturnValuesForAllMethods()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl.xml');
        $roles = array(
            'index' =>
                array('USER', 'ADMIN'),
            'admin' =>
                array('ADMIN')
        );
        $methods = array(
            'index' =>
                'index',
            'admin' =>
                'admin'
        );

        $this->assertEquals($roles, $loader->getOptionForController('roles', 'testController'));
        $this->assertEquals($methods, $loader->getOptionForController('method', 'testController'));
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArrayForUnknowMethodsOrControllersOrOptions()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl.xml');

        $this->assertEquals(array(), $loader->getOptionForControllerAndMethod('roles', '_not_exist', 'index'));
        $this->assertEquals(array(), $loader->getOptionForControllerAndMethod('roles', 'testController', '_not_exist'));
        $this->assertEquals(array(), $loader->getOptionForControllerAndMethod('roles', '_not_exist', '_not_exist'));
        $this->assertEquals(array(), $loader->getOptionForControllerAndMethod('_not_exist', 'testController', 'index'));
    }

    /**
     * @test
     */
    public function shouldReturnDefaultValuesForUndescribedMethods()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl_defaults.xml');

        $this->assertEquals(array('ADMIN'), $loader->getOptionForControllerAndMethod('roles','defaultController', 'other'));
        $this->assertEquals(array('USER'), $loader->getOptionForControllerAndMethod('roles', 'defaultController', 'index'));
    }

    /**
     * @test
     */
    public function shouldReturnDefaultValuesForUndescribedControllers()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl_defaults.xml');
        $this->assertEquals(array('DEFAULT_INDEX'), $loader->getOptionForControllerAndMethod('roles', 'otherController', 'index'));
    }

    /**
     * @test
     */
    public function shouldReturnDefaultRolesForUndescribedControllersAndMethods()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl_defaults.xml');
        $this->assertEquals(array('DEFAULT'), $loader->getOptionForControllerAndMethod('roles', 'otherController', 'other'));
    }

    /**
     * @test
     */
    public function shouldReturnAllRolesIncludingDefaultsForControllers()
    {
        $loader = new XmlConfigReader();
        $loader->load(__DIR__ . '/config/acl_defaults.xml');

        $roles = array(
            'index' =>
                array('USER'),
            '*' =>
                array('ADMIN')
        );

        $this->assertEquals($roles, $loader->getOptionForController('roles', 'defaultController'));

        $roles = array(
            'index' =>
                array('DEFAULT_INDEX'),
            '*' =>
                array('DEFAULT')
        );

        $this->assertEquals($roles, $loader->getOptionForController('roles', 'otherController'));


        $roles = array(
            'index' =>
                array('USER'),
            '*' =>
                array('DEFAULT')
        );

        $this->assertEquals($roles, $loader->getOptionForController('roles', 'noDefaultsController'));
    }

}

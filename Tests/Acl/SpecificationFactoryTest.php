<?php

use Mzz\MzzBundle\Acl\SpecificationFactory;
use Mzz\MzzBundle\Authentication\AuthenticationEnvironment;
use Mzz\MzzBundle\Acl\SpecificationTypes;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SpecificationFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function createsUserHasRoleSpecification()
    {
        $this->markTestSkipped('todo');
        $container = $this->getMock("Symfony\Component\DependencyInjection\ContainerBuilder");
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue(array('role')));

        $factory = new SpecificationFactory($container);
        $env = new AuthenticationEnvironment();

        $this->assertInstanceOf('Mzz\MzzBundle\Acl\UserHasRoleSpecification', $factory->create(SpecificationTypes::ROLE_BASED, $env));
    }

}

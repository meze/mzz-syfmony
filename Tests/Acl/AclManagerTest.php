<?php

namespace Tests\Authentication;
use \Mzz\MzzBundle\Authentication\Authentication;
use \Mzz\MzzBundle\Acl\AclManager;

class AclManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldReturnTrueIfSpecIsSatisfiedByAuth()
    {
        $this->assertTrue($this->createAclManagerThatReturns(true)->hasPermission(new StubAclAuthentication()));
    }

    /**
     * @test
     */
    public function shouldReturnFalseIfSpecIsNotSatisfiedByAuth()
    {
        $this->assertFalse($this->createAclManagerThatReturns(false)->hasPermission(new StubAclAuthentication()));
    }

    private function createAclManagerThatReturns($result)
    {
        $spec = $this->getMock("Mzz\MzzBundle\Acl\UserHasRoleSpecification", array('isSatisfiedBy'), array(array()));
        $spec->expects($this->once())
            ->method('isSatisfiedBy')
            ->will($this->returnValue($result));

        return new AclManager($spec);
    }

}

class StubAclAuthentication extends Authentication
{
    public function  __construct()
    {
    }
}

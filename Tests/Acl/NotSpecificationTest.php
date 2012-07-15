<?php

namespace Mzz\MzzBundle\Authentication;

use \Mzz\MzzBundle\Acl\CompositeSpecification;
use \Mzz\MzzBundle\Acl\NotSpecification;
use FOS\UserBundle\Model\UserInterface;

class TestMockSpecification extends CompositeSpecification
{
    public function isSatisfiedBy(UserInterface $candidate)
    {
        return true;
    }
}

class NotSpecificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldNotBeSatisfiedIfNot()
    {
        $auth = $this->mockAuth();

        $spec = new TestMockSpecification();
        $this->assertTrue($spec->isSatisfiedBy($auth));

        $spec = $spec->logicalNot();
        $this->assertFalse($spec->isSatisfiedBy($auth));
    }

    private function mockAuth()
    {
        return $this->getMock("FOS\UserBundle\Model\UserInterface", array(), array('user', 'pass', array()));
    }

}
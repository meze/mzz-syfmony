<?php

namespace Mzz\MzzBundle\Authentication;

use \Mzz\MzzBundle\Acl\CompositeSpecification;
use \Mzz\MzzBundle\Acl\NotSpecification;
use Mzz\MzzBundle\Authentication\Authentication;

class TestMockSpecification extends CompositeSpecification
{
    public function isSatisfiedBy(Authentication $candidate)
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
        return $this->getMock("Mzz\MzzBundle\Authentication\Authentication", array(), array('user', 'pass', array()));
    }

}
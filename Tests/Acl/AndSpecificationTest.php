<?php

namespace Mzz\MzzBundle\Authentication;

use \Mzz\MzzBundle\Acl\CompositeSpecification;
use \Mzz\MzzBundle\Acl\NotSpecification;
use FOS\UserBundle\Model\UserInterface;

class TestAndMockSpecification extends CompositeSpecification
{
    private $result;

    public function __construct($result)
    {
        $this->result = (bool)$result;
    }

    public function isSatisfiedBy(UserInterface $candidate)
    {
        return $this->result;
    }
}

class AndSpecificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldBeSatisfiedIfBothAreSatisfied()
    {
        $auth = $this->mockAuth();

        $spec1 = new TestAndMockSpecification(true);
        $spec2 = new TestAndMockSpecification(true);

        $this->assertTrue($spec1->logicalAnd($spec2)->isSatisfiedBy($auth));
    }

    /**
     * @test
     */
    public function shouldNotBeSatisfiedIfLeftOrRightIsNotSatisfied()
    {
        $auth = $this->mockAuth();

        $spec_true = new TestAndMockSpecification(true);
        $spec_false = new TestAndMockSpecification(false);

        $this->assertFalse($spec_true->logicalAnd($spec_false)->isSatisfiedBy($auth));
    }

    /**
     * @test
     */
    public function shouldNotBeSatisfiedIfBothAreNotSatisfied()
    {
        $auth = $this->mockAuth();

        $spec1 = new TestAndMockSpecification(false);
        $spec2 = new TestAndMockSpecification(false);

        $this->assertFalse($spec1->logicalAnd($spec2)->isSatisfiedBy($auth));
    }

    private function mockAuth()
    {
        return $this->getMock("FOS\UserBundle\Model\UserInterface", array(), array('user', 'pass', array()));
    }

}

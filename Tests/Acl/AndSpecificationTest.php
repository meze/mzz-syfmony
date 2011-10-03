<?php

namespace Mzz\MzzBundle\Authentication;

use \Mzz\MzzBundle\Acl\CompositeSpecification;
use \Mzz\MzzBundle\Acl\NotSpecification;
use Mzz\MzzBundle\Authentication\Authentication;

class TestAndMockSpecification extends CompositeSpecification
{
    private $result;

    public function __construct($result)
    {
        $this->result = (bool)$result;
    }

    public function isSatisfiedBy(Authentication $candidate)
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
        return $this->getMock("Mzz\MzzBundle\Authentication\Authentication", array(), array('user', 'pass', array()));
    }

}

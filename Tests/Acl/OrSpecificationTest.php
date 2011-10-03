<?php

namespace Mzz\MzzBundle\Authentication;

use \Mzz\MzzBundle\Acl\CompositeSpecification;
use \Mzz\MzzBundle\Acl\NotSpecification;
use Mzz\MzzBundle\Authentication\Authentication;

class TestOrMockSpecification extends CompositeSpecification
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

class OrSpecificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldBeSatisfiedIfOneIsSatisfied()
    {
        $auth = $this->mockAuth();

        $spec1 = new TestAndMockSpecification(true);
        $spec2 = new TestAndMockSpecification(false);

        $this->assertTrue($spec1->logicalOr($spec2)->isSatisfiedBy($auth));
    }

    /**
     * @test
     */
    public function shouldBeSatisfiedIfBothAreSatisfied()
    {
        $auth = $this->mockAuth();

        $spec1 = new TestAndMockSpecification(true);
        $spec2 = new TestAndMockSpecification(true);

        $this->assertTrue($spec1->logicalOr($spec2)->isSatisfiedBy($auth));
    }

    /**
     * @test
     */
    public function shouldNotBeSatisfiedIfLeftAndRightIsNotSatisfied()
    {
        $auth = $this->mockAuth();

        $spec_true = new TestAndMockSpecification(false);
        $spec_false = new TestAndMockSpecification(false);

        $this->assertFalse($spec_true->logicalOr($spec_false)->isSatisfiedBy($auth));
    }

    private function mockAuth()
    {
        return $this->getMock("Mzz\MzzBundle\Authentication\Authentication", array(), array('user', 'pass', array()));
    }

}
<?php

namespace Mzz\MzzBundle\Authentication;

use \Mzz\MzzBundle\Acl\UserHasRoleSpecification;

class UserHasRoleSpecificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldBeSatisfiedWhenUserHasAuthority()
    {
        $auth = $this->getAuth(array('ADMIN', 'EDITORS'));

        $spec = new UserHasRoleSpecification(array('USER', 'EDITORS'));
        $this->assertTrue($spec->isSatisfiedBy($auth));

        $spec = new UserHasRoleSpecification(array('ADMIN'));
        $this->assertTrue($spec->isSatisfiedBy($auth));
    }

    private function getAuth($authorities)
    {
        $auth = $this->getMock("FOS\UserBundle\Model\UserInterface", array(), array('user', 'pass', array()));
        $auth->expects($this->any())
            ->method('getRoles')
            ->will($this->returnValue($authorities));
        return $auth;
    }


}

/*

    public class HasReachedRentalThresholdSpecification : CompositeSpecification<CustomerAccount>
    {
        public override bool IsSatisfiedBy(CustomerAccount candidate)
        {
            return candidate.NumberOfRentalsThisMonth >= 5;
        }
    }
}*/
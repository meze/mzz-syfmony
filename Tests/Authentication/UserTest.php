<?php

namespace Mzz\MzzBundle\Authentication;


class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldReturnsDaoValues()
    {
        $userDetails = new User('admin', 'test', array('EDITORS'));

        $this->assertEquals('admin', $userDetails->getUsername());
        $this->assertEquals('test', $userDetails->getPassword());
        $this->assertTrue($userDetails->isAccountNonExpired());
        $this->assertTrue($userDetails->isCredentialsNonExpired());
        $this->assertTrue($userDetails->isEnabled());
        $this->assertEquals(array('EDITORS'), $userDetails->getAuthorities());
    }

    /**
     * @test
     */
    public function shouldBeFullyInactiveUser()
    {
        $userDetails = new User('admin', 'test', array('EDITORS'), false, false, false);

        $this->assertFalse($userDetails->isAccountNonExpired());
        $this->assertFalse($userDetails->isCredentialsNonExpired());
        $this->assertFalse($userDetails->isEnabled());
    }

    /**
     * @test
     */
    public function userMayHaveAnIdentity()
    {
        $userDetails = new User('admin', 'test', array('EDITORS'), false, false, false);

        $this->assertNull($userDetails->getIdentity());
        $userDetails->setIdentity(1234);
        $this->assertEquals(1234, $userDetails->getIdentity());
    }

}

?>

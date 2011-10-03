<?php

use Mzz\MzzBundle\Authentication\AnonymousAuthentication;

class AnonymousTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function anonymousShouldBeAlwaysNotAuthenticated()
    {
        $auth = new AnonymousAuthentication('anonymous', '', array('none'));

        $this->assertFalse($auth->isAuthenticated());
    }

    /**
     * @test
     */
    public function anonymousCannotBeSetToAuthenticated()
    {
        $auth = new AnonymousAuthentication('anonymous', '', array('none'));

        try
        {
            $auth->setAuthenticated(true);
            $this->fail();
        }
        catch (\InvalidArgumentException $e)
        {
            $this->assertFalse($auth->isAuthenticated());
        }
    }

    /**
     * @test
     */
    public function anonymousAuthoritiesCannotBeChanged()
    {
        $auth = new AnonymousAuthentication('anonymous', '', array('none'));

        try
        {
            $auth->setAuthorities(array('new'));
            $this->fail();
        }
        catch (\InvalidArgumentException $e)
        {
            $this->assertEquals(array('none'), $auth->getAuthorities());
        }
    }

    /**
     * @test
     */
    public function anonymousShouldHaveDefaultRoles()
    {
        $auth = new AnonymousAuthentication('anonymous', '', array('anonymous'));

        $this->assertEquals(array('anonymous'), $auth->getAuthorities());
    }
}

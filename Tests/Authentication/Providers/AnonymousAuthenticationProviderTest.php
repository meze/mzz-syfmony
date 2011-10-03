<?php

namespace Tests\Authentication;

use \Mzz\MzzBundle\Authentication\Providers\AnonymousAuthenticationProvider;
use \Mzz\MzzBundle\Authentication\AnonymousAuthentication;
use \Mzz\MzzBundle\Authentication\UserDetails;

class AnonymousAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function successAuthentication()
    {
        $provider = new AnonymousAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\AnonymousAuthentication', array(), array('', '', array()));

        $auth->expects($this->never())
            ->method('setAuthenticated');

        $this->assertEquals($auth, $provider->authenticate($auth));
    }

    /**
     * @test
     */
    public function supportsCertainTypeOfAuthentication()
    {
        $provider = new AnonymousAuthenticationProvider();
        $authA = $this->getMock('Mzz\MzzBundle\Authentication\Authentication', array(), array('', '', array()));
        $authB = new AnonymousAuthentication('', '', array());

        $this->assertFalse($provider->supports($authA));
        $this->assertTrue($provider->supports($authB));
    }

}
<?php

namespace Mzz\MzzBundle\Authentication\Tests;

use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\UsernamePasswordAuthentication;
use Mzz\MzzBundle\Authentication\Providers\AuthenticationProviderManager;

class AuthenticationProviderManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function providersAreAdded()
    {
        $providers = array();
        $providers[] = new MockProivder();
        $providers[] = new MockProivder();
        $providers[] = new MockProivder();

        $manager = new AuthenticationProviderManager();

        $manager->setProviders($providers);

        $this->assertEquals($providers, $manager->getProviders());
    }

    /**
     * @test
     */
    public function authenticationSucceed()
    {
        $auth = $this->mockAuth();
        $manager = new AuthenticationProviderManager();

        $manager->setProviders(array(new MockProivder()));

        $this->assertEquals($auth, $manager->authenticate($auth));
    }

    /**
     * @test
     */
    public function returnsAuthOnceAuthenticated()
    {
        $auth = $this->mockAuth();
        $providerA = $this->getMock('Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider');
        $providerB = $this->getMock('Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider');

        $providerA->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($auth));
        $providerA->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true));

        $providerB->expects($this->never())
            ->method('authenticate');
        $providerB->expects($this->any())
            ->method('supports')
            ->will($this->returnValue(true));


        $manager = new AuthenticationProviderManager();
        $manager->setProviders(array($providerA, $providerB));

        $manager->authenticate($auth);
    }

    /**
     * @test
     */
    public function triesToAuthenticateUsingOnlySupportedProviders()
    {
        $auth = $this->mockAuth();
        $providerA = $this->getMock('Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider');
        $providerB = $this->getMock('Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider');

        $providerA->expects($this->never())
            ->method('authenticate');
        $providerA->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(false));

        $providerB->expects($this->once())
            ->method('supports')
            ->will($this->returnValue(true));
        $providerB->expects($this->once())
            ->method('authenticate')
            ->will($this->returnValue($auth));

        $manager = new AuthenticationProviderManager();
        $manager->setProviders(array($providerA, $providerB));

        $manager->authenticate($auth);
    }

    private function mockAuth()
    {
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\Authentication', array(), array('user', 'pass', array()));
        return $auth;
    }

}

class MockProivder implements \Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider
{

    public function authenticate(Authentication $authentication)
    {
        return $authentication;
    }

    public function supports(Authentication $authentication)
    {
        return true;
    }

}
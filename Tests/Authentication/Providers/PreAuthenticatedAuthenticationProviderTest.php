<?php

namespace Tests\Authentication;

use \Mzz\MzzBundle\Authentication\Providers\PreAuthenticatedAuthenticationProvider;
use \Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication;
use \Mzz\MzzBundle\Authentication\UserDetails;

class PreAuthenticatedAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function successAuthentication()
    {
        $provider = new PreAuthenticatedAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication', array(), array('', '', array()));

        $auth->expects($this->once())
            ->method('setAuthenticated')
            ->with($this->equalTo(true));

        $this->assertEquals($auth, $provider->authenticate($auth));
    }

    /**
     * @test
     */
    public function returnsUserDetailsAfterSuccessAuthentication()
    {
        $provider = new PreAuthenticatedAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication', array(), array('user', '', array()));

        $repository = new StubUserRepository();

        $auth->expects($this->once())
            ->method('setPrincipal')
            ->with($this->equalTo($repository->findByUsername('user')));

        $provider->setUserRepository($repository);
        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function shouldNotChangeGivenUserDetailsAfterSuccessAuthentication()
    {
        $repository = new StubUserRepository();

        $provider = new PreAuthenticatedAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication', array(), array($repository->findByUsername('user'), '', array()));

        $auth->expects($this->never())
            ->method('setPrincipal');

        $auth->expects($this->any())
            ->method('getPrincipal')
            ->will($this->returnValue($repository->findByUsername('user')));

        $provider->authenticate($auth);
        $provider->setUserRepository($repository);
        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function shouldChangeAuthoritiesAfterSuccessAuthentication()
    {
        $repository = new StubUserRepository();
        $provider = new PreAuthenticatedAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication', array(), array('', '', array()));

        $auth->expects($this->any())
            ->method('getPrincipal')
            ->will($this->returnValue($repository->findByUsername('user')));

        $auth->expects($this->once())
            ->method('setAuthorities')
            ->with($this->equalTo(array('user')));

        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function shouldNotChangeAuthoritiesWithoutPrincipal()
    {
        $provider = new PreAuthenticatedAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication', array(), array('', '', array()));

        $auth->expects($this->never())
            ->method('setAuthorities');

        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function shouldNotChangeAuthoritiesIfItAlreadyHasThem()
    {
        $repository = new StubUserRepository();
        $provider = new PreAuthenticatedAuthenticationProvider();
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication', array(), array('', '', array()));

        $auth->expects($this->any())
            ->method('getPrincipal')
            ->will($this->returnValue($repository->findByUsername('user')));

        $auth->expects($this->once())
            ->method('getAuthorities')
            ->will($this->returnValue(array('user')));

        $auth->expects($this->never())
            ->method('setAuthorities');

        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function supportsCertainTypeOfAuthentication()
    {
        $provider = new PreAuthenticatedAuthenticationProvider();
        $authA = $this->getMock('Mzz\MzzBundle\Authentication\Authentication', array(), array('', '', array()));
        $authB = new PreAuthenticatedAuthentication('', '', array());

        $this->assertFalse($provider->supports($authA));
        $this->assertTrue($provider->supports($authB));
    }


}

class StubUserRepository implements \Mzz\MzzBundle\Authentication\UserRepository
{
    private $user;
    public function createEmptyUser() {}
    public function findRememberMeTokenByUserIdAndToken($id, $token) {}
    public function findByUsername($username)
    {
        if (!$this->user)
            $this->user = new \Mzz\MzzBundle\Authentication\User($username, 'test', array('user'));
        return $this->user;
    }


    public function createNewRememberMeTokenFor($user) {}
    public function removeAllRememberMeTokensFor($user) {}
    public function removeRememberMeToken($user, $token) {}
}

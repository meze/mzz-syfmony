<?php

use Mzz\MzzBundle\Authentication\Providers\DaoAuthenticationProvider;
use Mzz\MzzBundle\Authentication\Exceptions\BadCredentialException;

class DaoAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function whenGivenCorrectPasswordAndUsernameShouldBeSuccessfulyAuthenticated()
    {
        $user = $this->getMock('Mzz\MzzBundle\Authentication\User', array(), array('user', 'pass', array('ADMIN')));

        $user->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue('pass'));

        $provider = $this->getProvider($user);
        $auth = $this->getAuthentication();

        $auth = $provider->authenticate($auth);
        $this->assertTrue($auth->isAuthenticated());
        $this->assertEquals($user, $auth->getPrincipal());
        $this->assertEquals('pass', $auth->getCredentials());
    }

    /**
     * @test
     */
    public function successAuthenticationWithSalt()
    {
        $user = $this->getMock('Mzz\MzzBundle\Authentication\User', array(), array('user', 'pass', array('ADMIN')));

        $user->expects($this->any())
            ->method('getPassword')
            ->will($this->returnValue('passsalt'));

        $provider = $this->getProvider($user);
        $provider->setSalt('salt');
        $auth = $this->getAuthentication();

        $auth = $provider->authenticate($auth);
        $this->assertTrue($auth->isAuthenticated());
    }

    /**
     * @test
     */
    public function credentialsMismatch()
    {
        try
        {
            $auth = $this->getAuthentication();
            // don't mock the getPassword because it already returns different value - null
            $user = $this->getMock('Mzz\MzzBundle\Authentication\User', array(), array('user', '', array('ADMIN')));
            $provider = $this->getProvider($user);
            $provider->authenticate($auth);
            $this->fail("The different credentials should throw an exception");
        }
        catch (BadCredentialException $e)
        {

        }
    }

    /**
     * @test
     */
    public function customPasswordEncoderIsUsed()
    {
        $encoder = $this->getMock('Mzz\MzzBundle\Authentication\Encoders\PasswordEncoder');


        $encoder->expects($this->once())
            ->method('isPasswordValid')
            ->will($this->returnValue(true));

        $auth = $this->getAuthentication();
        $user = $this->getMock('Mzz\MzzBundle\Authentication\User', array(), array('user', '', array('ADMIN')));

        $provider = new DaoAuthenticationProvider();
        $provider->setUser($user);
        $provider->setPasswordEncoder($encoder);

        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function supportsCertainTypeOfAuthentication()
    {
        $provider = new DaoAuthenticationProvider();
        $authA = $this->getMock('Mzz\MzzBundle\Authentication\Authentication', array(), array('', '', array()));
        $authB = new Mzz\MzzBundle\Authentication\UsernamePasswordAuthentication('', '', array());

        $this->assertFalse($provider->supports($authA));
        $this->assertTrue($provider->supports($authB));
    }

    private function getAuthentication($username = 'user', $password = 'pass', $authorities = array('ADMIN'))
    {
        $auth = $this->getMock('Mzz\MzzBundle\Authentication\Authentication', array(), array($username, $password, $authorities));

        $auth->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue($username));

        $auth->expects($this->once())
            ->method('getCredentials')
            ->will($this->returnValue($password));

        $auth->expects($this->any())
            ->method('getAuthorities')
            ->will($this->returnValue($authorities));


        return $auth;
    }

    private function getProvider($user)
    {
        $provider = new DaoAuthenticationProvider();
        $provider->setUser($user);
        return $provider;
    }

}

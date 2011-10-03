<?php


use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\User;

class SimpleAuthentication extends Authentication
{
}

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldHaveInitialValues()
    {
        $authorities = array('test', 'editor');
        $auth = new SimpleAuthentication("username", "password", $authorities);

        $this->assertEquals('username', $auth->getPrincipal());
        $this->assertEquals('password', $auth->getCredentials());
        $this->assertEquals($authorities, $auth->getAuthorities());
    }

    /**
     * @test
     */
    public function shouldNotBeAuthenticated()
    {
        $auth = new SimpleAuthentication("username", "password", array());
        $this->assertFalse($auth->isAuthenticated());
    }

    /**
     * @test
     */
    public function authenticatedChangesItsValue()
    {
        $auth = new SimpleAuthentication("username", "password", array());
        $this->assertFalse($auth->isAuthenticated());
        $auth->setAuthenticated(true);
        $this->assertTrue($auth->isAuthenticated());
        $auth->setAuthenticated(false);
        $this->assertFalse($auth->isAuthenticated());
    }

    /**
     * @test
     */
    public function updatesAuthorities()
    {
        $auth = new SimpleAuthentication("username", "password", array());
        $this->assertEquals(array(), $auth->getAuthorities());

        $auth->setAuthorities($new = array(1, 2, 3));

        $this->assertEquals($new, $auth->getAuthorities());
    }

    /**
     * @test
     */
    public function shouldSetNewPrincipal()
    {
        $auth = new SimpleAuthentication("username", "password", array());
        $this->assertEquals('username', $auth->getPrincipal());

        $auth->setPrincipal($new = 'admin');

        $this->assertEquals($new, $auth->getPrincipal());
    }

    /**
     * @test
     */
    public function shouldReturnPrincipalNameAndIdentity()
    {
        $auth = new SimpleAuthentication("username", "password", array());
        $this->assertNull($auth->getPrincipalName());
        $this->assertNull($auth->getPrincipalIdentity());


        $user = $this->getMock("Mzz\MzzBundle\Authentication\User", array(), array('user', 'pass', array()));


        $user->expects($this->once())
            ->method('getUsername')
            ->will($this->returnValue('user'));
        $user->expects($this->once())
            ->method('getIdentity')
            ->will($this->returnValue(123));

        $auth->setPrincipal($user);


        $this->assertEquals('user', $auth->getPrincipalName());
        $this->assertEquals(123, $auth->getPrincipalIdentity());
    }

}

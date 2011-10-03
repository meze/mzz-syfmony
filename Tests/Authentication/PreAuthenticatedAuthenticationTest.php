<?php

use Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication;

class PreAuthenticatedAuthenticationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldNotBeAlwaysAuthenticated()
    {
        $auth = new PreAuthenticatedAuthentication("username", "password", array());
        $this->assertFalse($auth->isAuthenticated());
    }
}

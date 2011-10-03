<?php

use Mzz\MzzBundle\Authentication\Authentication;

class SecurityContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function returnsCorrectAuthentication()
    {
        $sc = new Mzz\MzzBundle\Authentication\SecurityContext();
        $auth = $this->getMock("Mzz\MzzBundle\Authentication\Authentication", array(), array('user', 'pass', array()));
        $sc->setAuthentication($auth);
        $this->assertEquals($auth, $sc->getAuthentication());
    }
    
}
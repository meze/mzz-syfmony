<?php

class Sha1PasswordEncoderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function passwordEqualToHashIsValid()
    {
        $hash = 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'; // sha1 hash of the string 'test'
        $encoder = new Mzz\MzzBundle\Authentication\Encoders\Sha1PasswordEncoder();
        $this->assertTrue($encoder->isPasswordValid($hash, 'test'));
    }

    /**
     * @test
     */
    public function passwordEqualToHashWithSaltIsValid()
    {
        $hash = '7288edd0fc3ffcbe93a0cf06e3568e28521687bc'; // sha1 hash of the string 'test' with a salt '123'
        $encoder = new Mzz\MzzBundle\Authentication\Encoders\Sha1PasswordEncoder();
        $this->assertTrue($encoder->isPasswordValid($hash, 'test', '123'));
    }

    /**
     * @test
     */
    public function passwordWithAnotherHashIsInvalid()
    {
        $hash = '7288edd0fc3ffcbe93a0cf06e3568e28521687bc'; // sha1 hash of the string 'test' with a salt '123'
        $encoder = new Mzz\MzzBundle\Authentication\Encoders\Sha1PasswordEncoder();
        $this->assertFalse($encoder->isPasswordValid($hash, ''));
        $this->assertFalse($encoder->isPasswordValid($hash, '2424242'));
        $this->assertFalse($encoder->isPasswordValid($hash, 'test', '1234'));
    }

}

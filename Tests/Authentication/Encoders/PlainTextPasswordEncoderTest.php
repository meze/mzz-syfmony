<?php

class PlainTextPasswordEncoderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function equalPasswordsShouldBeEqual()
    {
        $encoder = new \Mzz\MzzBundle\Authentication\Encoders\PlainTextPasswordEncoder();
        $this->assertTrue($encoder->isPasswordValid('test', 'test'));
        $this->assertTrue($encoder->isPasswordValid('', ''));
    }

    /**
     * @test
     */
    public function passwordWithSaltShouldBeValid()
    {
        $encoder = new \Mzz\MzzBundle\Authentication\Encoders\PlainTextPasswordEncoder();
        $this->assertTrue($encoder->isPasswordValid('testsalt', 'test', 'salt'));
    }

    /**
     * @test
     */
    public function differentPasswordsShouldNotBeEqual()
    {
        $encoder = new \Mzz\MzzBundle\Authentication\Encoders\PlainTextPasswordEncoder();
        $this->assertFalse($encoder->isPasswordValid('test', 'test1'));
        $this->assertFalse($encoder->isPasswordValid('', 'tt'));
        $this->assertFalse($encoder->isPasswordValid('tt', ''));
    }

}

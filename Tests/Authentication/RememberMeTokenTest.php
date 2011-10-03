<?php

use Mzz\MzzBundle\Authentication\RememberMeToken;

class RememberMeTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function tokenIsValidOnlyIfTokenIsEqual()
    {
        $token = 'abcdef12345';
        $series = 'zxcv1234';
        $principal = 1;

        $token1 = new RememberMeToken($principal, $token, $series);
        $token2 = new RememberMeToken($principal, 'not equal token', $series);

        $this->assertTrue($token1->isValid($token1));
        $this->assertFalse($token1->isValid($token2));
    }

    /**
     * @test
     */
    public function tokenCanBeGeneratedAndUpdated_WhereasSeriesIsPermanent()
    {
        $token_str = 'abcdef12345';
        $series = 'zxcv1234';
        $principal = 1;

        $token = new RememberMeToken($principal, $token_str, $series);
        $token->renewToken();

        $this->assertNotEquals($token_str, $token->getToken());
        $this->assertNotNull($token->getToken());
    }


    /**
     * @test
     */
    public function createsTokenWithRandomSeriesAndToken()
    {
        $token1 = RememberMeToken::createFor($principal1 = 1);
        $token2 = RememberMeToken::createFor($principal2 = 2);

        $this->assertNotNull($token1->getSeries());
        $this->assertNotNull($token1->getToken());

        $this->assertEquals($principal1, $token1->getPrincipal(), "principal #1");
        $this->assertEquals($principal2, $token2->getPrincipal(), "principal #2");

        $this->assertNotEquals($token1->getSeries(), $token2->getSeries(), "should be different series");
        $this->assertNotEquals($token1->getToken(), $token2->getToken(), "should be different tokens");

        $this->assertNotEquals($token2->getSeries(), $token2->getToken(), "different series and token");
    }

    /**
     * @test
     */
    public function convertsItselfToString()
    {
        $token = 'abcdef12345';
        $series = 'zxcv1234';
        $principal = 1;

        $token = new RememberMeToken($principal, $token, $series);

        $this->assertEquals('1;abcdef12345;zxcv1234', $token->__toString());
    }

    /**
     * @test
     */
    public function createTokenObjectFromString()
    {
        $token = RememberMeToken::createFromString('11;abcdef12345;zxcv1234');

        $this->assertEquals('11', $token->getPrincipal());
        $this->assertEquals('zxcv1234', $token->getSeries());
        $this->assertEquals('abcdef12345', $token->getToken());
    }

    /**
     * @test
     */
    public function shouldNotCreateTokenFromInvalidStrings()
    {
        $this->assertEquals(RememberMeToken::NO_TOKEN, RememberMeToken::createFromString('11;abcdef12345zxcv1234'));
        $this->assertEquals(RememberMeToken::NO_TOKEN, RememberMeToken::createFromString(''));
        $this->assertEquals(RememberMeToken::NO_TOKEN, RememberMeToken::createFromString('12;12;12;12;;211'));
    }

}

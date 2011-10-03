<?php

namespace Mzz\MzzBundle\Authentication;

class RememberMeToken
{
    const NO_TOKEN = null;

    private $principal;
    private $token;
    private $series;

    public function __construct($principal, $token, $series)
    {
        $this->principal = $principal;
        $this->token = $token;
        $this->series = $series;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function isValid(RememberMeToken $token)
    {
        return $this->token === $token->getToken();
    }

    public function getPrincipal()
    {
        return $this->principal;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function renewToken()
    {
        $this->token = self::getRandomString();
        return $this;
    }

    public function __toString()
    {
        return $this->principal . ';' . $this->token . ';' . $this->series;
    }

    public static function createFor($principal)
    {
        return new self($principal, self::getRandomString(), self::getRandomString());
    }

    public static function createFromString($token)
    {
        $token = \explode(';', $token);
        if (count($token) === 3)
            return new self($token[0], $token[1], $token[2]);
        return self::NO_TOKEN;
    }

    private static function getRandomString()
    {
        return \sha1(\mt_rand() . 'it is monday!' . \md5(\mt_rand()));
    }
}
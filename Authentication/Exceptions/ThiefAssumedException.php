<?php

namespace Mzz\MzzBundle\Authentication\Exceptions;

class ThiefAssumedException extends AuthenticationException
{
    private $token;
    private $series;


    public function __construct($token, $series, $code = 0, $previous = null)
    {
        $this->token = $token;
        $this->series = $series;
        parent::__construct('Thief was assumed during authentication.', $code, $previous);
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getValidatedSeries()
    {
        return $this->series;
    }
}

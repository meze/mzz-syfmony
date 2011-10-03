<?php

namespace Mzz\MzzBundle\Authentication\Encoders;

class PlainTextPasswordEncoder implements PasswordEncoder
{

    public function isPasswordValid($encoded, $raw, $salt = '')
    {
        return (string)$encoded === (string)$raw . $salt;
    }
}

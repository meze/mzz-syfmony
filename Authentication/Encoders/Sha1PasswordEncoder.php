<?php

namespace Mzz\MzzBundle\Authentication\Encoders;

class Sha1PasswordEncoder implements PasswordEncoder
{
    public function isPasswordValid($encoded, $raw, $salt = '')
    {
        return (string)$encoded == \sha1($raw . $salt);
    }
}

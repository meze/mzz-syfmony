<?php

namespace Mzz\MzzBundle\Authentication\Encoders;

interface PasswordEncoder
{
    public function isPasswordValid($encoded, $raw, $salt = '');
}

<?php

namespace Mzz\MzzBundle\Authentication;
use Mzz\MzzBundle\Authentication\Authentication;

class AnonymousAuthentication extends Authentication
{
    public function setAuthenticated($authenticated)
    {
        throw new \InvalidArgumentException('Anonymous cannot be authenticated.');
    }

    public function setAuthorities($authorities)
    {
        throw new \InvalidArgumentException('Anonymous authorities cannot be changed via this method. You should set default authorities in your configuration file.');
    }
}

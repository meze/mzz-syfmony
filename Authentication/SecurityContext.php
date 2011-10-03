<?php

namespace Mzz\MzzBundle\Authentication;

class SecurityContext
{
    private $authentication;

    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }

    public function getAuthentication()
    {
        return $this->authentication;
    }
}

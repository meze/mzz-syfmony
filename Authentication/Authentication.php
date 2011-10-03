<?php

namespace Mzz\MzzBundle\Authentication;
use Mzz\MzzBundle\Authentication\IUserDetails;

abstract class Authentication
{
    private $principal;
    private $credentials;
    private $authorities;
    private $authenticated = false;
    private $details;

    /**
     * @todo add additional details
     */
    public function  __construct($principal, $credentials, $authorities, $details = array())
    {
        $this->credentials = $credentials;
        $this->authorities = $authorities;
        $this->principal = $principal;
        $this->details = $details;
    }

    public function isAuthenticated() {
        return $this->authenticated;
    }

    public function setAuthenticated($authenticated)
    {
        $this->authenticated = (bool)$authenticated;
    }

    public function getAuthorities()
    {
        return $this->authorities;
    }

    public function setAuthorities($authorities)
    {
        $this->authorities = $authorities;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function getPrincipal()
    {
        return $this->principal;
    }

    public function setPrincipal($principal)
    {
        $this->principal = $principal;
    }

    public function getPrincipalName()
    {
        return $this->principal instanceof User ? $this->principal->getUsername() : null;
    }

    public function getPrincipalIdentity()
    {
        return $this->principal instanceof User ? $this->principal->getIdentity() : null;
    }
}

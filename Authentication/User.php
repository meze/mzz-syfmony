<?php

namespace Mzz\MzzBundle\Authentication;

class User implements UserDetails
{
    private $password;
    private $passwordSalt;
    private $username;
    private $authorities;
    private $accountNonExpired;
    private $credentialsNonExpired;
    private $enabled;
    private $remembered;
    private $identity = null;

    public function  __construct($username, $password, $authorities, $enabled = true, $accountNonExpired = true, $credentialsNonExpired = true)
    {
        $this->setUsername($username);
        $this->setPassword($password);

        $this->enabled = $enabled;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonExpired = $accountNonExpired;
        $this->authorities = $authorities;
    }

    public function getAuthorities()
    {
        return $this->authorities;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername()
    {
        return (string)$this->username;
    }

    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function isRemembered()
    {
        return $this->remembered;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPasswordSalt($salt)
    {
        $this->passwordSalt = $salt;
    }

    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }

    public function setRemembered($remembered)
    {
        $this->remembered = (bool)$remembered;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    public function __toString()
    {
        return (string)$this->getIdentity();
    }
}

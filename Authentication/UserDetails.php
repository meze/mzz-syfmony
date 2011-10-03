<?php

namespace Mzz\MzzBundle\Authentication;

interface UserDetails
{
    function getAuthorities();

    function getPassword();

    function getPasswordSalt();

    function setPasswordSalt($salt);

    function getUsername();

    function setPassword($password);

    function setUsername($username);

    function isAccountNonExpired();

    function isCredentialsNonExpired();

    function isEnabled();

    function isRemembered();

    function setRemembered($remembered);

    function getIdentity();

    function setIdentity($identity);
}

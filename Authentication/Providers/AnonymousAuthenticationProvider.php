<?php

namespace Mzz\MzzBundle\Authentication\Providers;
use Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider;
use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\AnonymousAuthentication;
use Mzz\MzzBundle\Authentication\UserRepository;

class AnonymousAuthenticationProvider implements AuthenticationProvider
{
    public function authenticate(Authentication $authentication)
    {
        return $authentication;
    }

    public function supports(Authentication $authentication)
    {
        return $authentication instanceof AnonymousAuthentication;
    }
}

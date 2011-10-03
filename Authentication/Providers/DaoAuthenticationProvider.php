<?php

namespace Mzz\MzzBundle\Authentication\Providers;

use Mzz\MzzBundle\Authentication\UserDetails;
use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\UsernamePasswordAuthentication;
use Mzz\MzzBundle\Authentication\Exceptions\BadCredentialException;
use Mzz\MzzBundle\Authentication\Encoders\PasswordEncoder;
use Mzz\MzzBundle\Authentication\Encoders\PlainTextPasswordEncoder;

class DaoAuthenticationProvider implements AuthenticationProvider
{
    private $user;
    private $passwordEncoder;
    private $salt = '';

    public function  __construct(PasswordEncoder $passwordEncoder = null)
    {
        $this->setPasswordEncoder($passwordEncoder ? $passwordEncoder : new PlainTextPasswordEncoder());
    }


    public function authenticate(Authentication $authentication)
    {
        if (!$this->passwordEncoder->isPasswordValid($this->user->getPassword(), $authentication->getCredentials(), $this->getSalt()))
            throw new BadCredentialException('The credentials of the user mismatched');

        /* @todo pass authorities? */
        $auth = new UsernamePasswordAuthentication($this->user, $this->user->getPassword(), array());
        $auth->setAuthenticated(true);
        return $auth;
    }

    public function supports(Authentication $authentication)
    {
        return $authentication instanceof UsernamePasswordAuthentication;
    }

    public function setUser(UserDetails $user)
    {
        $this->user = $user;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setPasswordEncoder(PasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getPasswordEncoder()
    {
        return $this->passwordEncoder;
    }
}

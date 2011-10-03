<?php

namespace Mzz\MzzBundle\Authentication\Providers;

use Mzz\MzzBundle\Authentication\UserRepository;
use Mzz\MzzBundle\Authentication\Authentication;

abstract class UserRepositoryGateway
{
    protected $userRepository;

    public function setUserRepository(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    protected function principalShouldBeRetrieved(Authentication $authentication)
    {
        return !is_object($authentication->getPrincipal()) && $this->userRepository instanceof UserRepository;
    }

    protected function retrieveAndUpdatePrincipal(Authentication $authentication)
    {
        $authentication->setPrincipal($this->findPrincipal($authentication));
        return $authentication;
    }

    protected function authoritiesShouldBeUpdated(Authentication $authentication)
    {
        return is_object($authentication->getPrincipal()) && $authentication->getAuthorities() == null;
    }

    protected function updateAuthorities(Authentication $authentication)
    {
        $authentication->setAuthorities($authentication->getPrincipal()->getAuthorities());
        return $authentication;
    }

    abstract protected function findPrincipal(Authentication $authentication);
}
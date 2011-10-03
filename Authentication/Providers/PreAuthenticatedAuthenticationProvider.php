<?php

namespace Mzz\MzzBundle\Authentication\Providers;
use Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider;
use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\PreAuthenticatedAuthentication;

class PreAuthenticatedAuthenticationProvider extends UserRepositoryGateway implements AuthenticationProvider
{
    const MZZ_USERID_SESSION_NAME = '_mzz_auth_userid';

    public function authenticate(Authentication $authentication)
    {
        $authentication->setAuthenticated(true);
        if ($this->principalShouldBeRetrieved($authentication)) {
            $this->retrieveAndUpdatePrincipal($authentication);
        }

        if ($this->authoritiesShouldBeUpdated($authentication)) {
            $this->updateAuthorities($authentication);
        }
        return $authentication;
    }

    public function supports(Authentication $authentication)
    {
        return $authentication instanceof PreAuthenticatedAuthentication;
    }

    protected function findPrincipal(Authentication $authentication)
    {
        return $this->userRepository->findByUsername($authentication->getPrincipal());
    }

}

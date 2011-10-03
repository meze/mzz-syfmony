<?php

namespace Mzz\MzzBundle\Authentication\Providers;

use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\RememberMeAuthentication;
use Mzz\MzzBundle\Authentication\Exceptions\ThiefAssumedException;

class RememberMeAuthenticationProvider extends UserRepositoryGateway implements AuthenticationProvider
{
    public function supports(Authentication $authentication)
    {
        return $authentication instanceof RememberMeAuthentication;
    }

    public function authenticate(Authentication $authentication)
    {
        $token_auth = $authentication->getToken();
        $token = $this->findPrincipal($authentication);

        if (!empty($token_auth) && !empty($token)) {

            if (!$token->isValid($token_auth)) {
                throw new ThiefAssumedException($token_auth, $token->getSeries());
            }

            $authentication->setAuthenticated(true);
            if ($this->principalShouldBeRetrieved($authentication))
                $authentication->setPrincipal($token->getPrincipal());

            if ($this->authoritiesShouldBeUpdated($authentication))
                $this->updateAuthorities($authentication);
        }

        return $authentication;
    }


    protected function findPrincipal(Authentication $authentication)
    {
        return $this->userRepository->findRememberMeTokenByUserIdAndToken($authentication->getPrincipal(), $authentication->getToken());
    }


}

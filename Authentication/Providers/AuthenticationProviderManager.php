<?php

namespace Mzz\MzzBundle\Authentication\Providers;

use Mzz\MzzBundle\Authentication\Authentication;

class AuthenticationProviderManager
{

    private $providers;

    public function authenticate(Authentication $auth)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($auth))
                return $provider->authenticate($auth);
        }
    }

    public function setProviders(array $providers)
    {
        $this->providers = $providers;
    }

    public function getProviders()
    {
        return $this->providers;
    }
}

<?php

namespace Mzz\MzzBundle\Authentication\Providers;

use Mzz\MzzBundle\Authentication\Authentication;

interface AuthenticationProvider
{
    public function authenticate(Authentication $authentication);

    public function supports(Authentication $authentication);
}
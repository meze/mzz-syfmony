<?php

namespace Mzz\MzzBundle\Authentication;

class PreAuthenticatedAuthentication extends Authentication
{

    public function __construct($principal, $credentials = '', $authorities = array(), $details = array())
    {
        parent::__construct($principal, $credentials, $authorities, $details);
    }

}

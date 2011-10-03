<?php

namespace Mzz\MzzBundle\Authentication;
use Mzz\MzzBundle\Authentication\Authentication;

class RememberMeAuthentication extends Authentication
{
    const COOKIE_NAME = '_rm_auth';

    private $token;

    public function __construct($principal, $token, $authorities = array(), $details = array())
    {
        $this->token = $token;
        parent::__construct($principal, '', $authorities, $details);
    }

    public function getToken()
    {
        return $this->token;
    }
    
    public function getTokenSeries()
    {
        return $this->token->getSeries();
    }

}

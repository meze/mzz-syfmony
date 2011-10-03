<?php

namespace Mzz\MzzBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\HelperInterface;
use Mzz\MzzBundle\Authentication\Authentication;

class SecurityHelper implements HelperInterface
{
    protected $charset = 'UTF-8';
    protected $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function isAuthenticated()
    {
        return $this->authentication->isAuthenticated();
    }

    public function getPrincipalName()
    {
        return $this->authentication->getPrincipalName();
    }

    public function getName()
    {
        return 'security';
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
}
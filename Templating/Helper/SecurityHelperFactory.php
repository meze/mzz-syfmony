<?php

namespace Mzz\MzzBundle\Templating\Helper;
use Mzz\MzzBundle\Authentication\SecurityContext;

class SecurityHelperFactory
{
    static function getInstance($class, SecurityContext $context)
    {
        return new $class($context->getAuthentication());
    }
}

<?php

namespace Mzz\MzzBundle\Templating\Extension;
use Mzz\MzzBundle\Authentication\SecurityContext;

class SecurityExtension extends \Twig_Extension
{
    private $context;
    private $authentication;

    public function __construct(SecurityContext $context = null)
    {
        $this->authentication = $context->getAuthentication();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'is_authenticated' => new \Twig_Function_Method($this, 'isAuthenticated'),
            'principal_name' => new \Twig_Function_Method($this, 'getPrincipalName'),
        );
    }

    public function isAuthenticated()
    {
        return $this->authentication->isAuthenticated();
    }

    public function getPrincipalName()
    {
        return $this->authentication->getPrincipalName();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'security';
    }
}

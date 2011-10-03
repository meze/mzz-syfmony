<?php

namespace Mzz\MzzBundle\HttpKernel\DataCollector;

use Mzz\MzzBundle\Authentication\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * SecurityDataCollector.
 *
 */
class SecurityDataCollector extends DataCollector
{
    protected $context;

    public function __construct(SecurityContext $context = null)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        if (null === $this->context) {
            $this->data = array(
                'enabled'       => false,
                'authenticated' => false,
                'user'          => '',
                'roles'         => array(),
            );
        } elseif (null === $auth = $this->context->getAuthentication()) {
            $this->data = array(
                'enabled'       => true,
                'authenticated' => false,
                'user'          => '',
                'roles'         => array(),
            );
        } else {
            $this->data = array(
                'enabled'       => true,
                'authenticated' => $auth->isAuthenticated(),
                'user'          => (string) $auth->getPrincipalName(),
                'roles'         => $auth->getAuthorities(),
            );
        }
    }

    /**
     * Checks if security is enabled.
     *
     * @return Boolean true if security is enabled, false otherwise
     */
    public function isEnabled()
    {
        return $this->data['enabled'];
    }

    /**
     * Gets the user.
     *
     * @return string The user
     */
    public function getUser()
    {
        return $this->data['user'];
    }

    /**
     * Gets the user.
     *
     * @return string The user
     */
    public function getRoles()
    {
        return $this->data['roles'];
    }

    /**
     * Checks if the user is authenticated or not.
     *
     * @return Boolean true if the user is authenticated, false otherwise
     */
    public function isAuthenticated()
    {
        return $this->data['authenticated'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'security';
    }
}

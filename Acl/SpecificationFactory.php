<?php

namespace Mzz\MzzBundle\Acl;

use Mzz\MzzBundle\Authentication\AuthenticationEnvironment;

class SpecificationFactory
{
    private $container;


    public function __construct($container)
    {
        $this->container = $container;
    }

    public function create($class, AuthenticationEnvironment $env)
    {
        $config = $this->container->get('mzz.acl.config');
        $spec = null;
        switch ($class) {
            case "Mzz\MzzBundle\Acl\UserHasRoleSpecification":
                $roles = $config->getOptionForControllerAndMethod('roles', $env->getClass(), $env->getMethod());
                $spec = new $class($roles);
                break;
            default:
                if (!class_exists($class)) {
                    throw new \RuntimeException(sprintf('Specification class "%s" for controller "%s" and method "%s" is not found',
                        $class, $env->getClass(), $env->getMethod()));
                }
                $spec = new $class;
        }

        return $spec;
    }
}

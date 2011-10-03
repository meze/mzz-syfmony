<?php

namespace Mzz\MzzBundle\Listener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\Container;
use Mzz\MzzBundle\Authentication\Providers\PreAuthenticatedAuthenticationProvider;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Mzz\MzzBundle\Authentication\AuthenticationEnvironment;

class AclRequestListener
{
    private $container;
    private $logger;

    public function __construct(Container $container, LoggerInterface $logger = null)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    public function onCoreController(FilterControllerEvent $event)
    {
        /* @todo probably it will be better to allow to configure whether subrequests should be acl-aware or not
         * UNCOMMETING THIS WILL CHECK ACL RULES ONLY FOR MASTER REQUEST
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getParameter('request_type')) {
            return $value;
        }*/

        $value = $event->getController();
        $subRequest = $this->container->get('request')->duplicate();
        $resolver = $this->container->get('controller_resolver');

        $acl_manager = $this->container->get('mzz.acl.manager');
        $config = $this->container->get('mzz.acl.config');
        $specification_factory = $this->container->get('mzz.acl.specification_factory');
        $specification_class = $this->container->getParameter('mzz.acl.default_specification');
        $auth = $this->container->get('mzz.authentication.security_context')->getAuthentication();

        $env = new AuthenticationEnvironment();
        $env->setRequest($this->container->get('request'));
        if ($this->hasClassAndMethodNames($value)) {
            $controller_class = get_class($value[0]);
            $controller_method = $value[1];
            $env->setClass($controller_class);
            $env->setMethod($controller_method);
            if ($custom_specification_class = $config->getOptionForControllerAndMethod('class', get_class($value[0]), $value[1])) {
                $specification_class = $custom_specification_class;
            }
            if ($this->logger) {
                $this->logger->info(sprintf('ACL: Checking access for %s:%s using %s', $controller_class, $controller_method, $custom_specification_class));
            }
        }

        $spec = $specification_factory->create($specification_class, $env);
        $acl_manager->setSpecification($spec);

        if ($acl_manager->hasPermission($auth)) {
            return $event;
        }

        $handler = $this->container->get('mzz.acl.access_denied_controller_resolver');
        $event->setController($handler->getController($value, $subRequest));
    }

    private function hasClassAndMethodNames($value)
    {
        return \is_array($value) && count($value) === 2;
    }
}

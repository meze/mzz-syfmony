<?php

namespace Mzz\MzzBundle\Acl;
use Mzz\MzzBundle\Request\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedControllerResolver
{
    private $controllerResolver;
    private $controllerServiceId;
    private $controllerMethod;

    public function getController($controller, Request $request)
    {
        $request->attributes->set('_controller', $this->getControllerName());
        return $this->controllerResolver->getController($request);
    }

    public function setControllerResolver($controller_resolver)
    {
        $this->controllerResolver = $controller_resolver;
    }

    public function setControllerServiceId($service_id)
    {
        $this->controllerServiceId = $service_id;
    }

    public function setControllerMethod($method)
    {
        $this->controllerMethod = $method;
    }

    private function getControllerName()
    {
        return $this->controllerServiceId . ':' . $this->controllerMethod;
    }

}
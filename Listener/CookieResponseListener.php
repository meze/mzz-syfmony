<?php

namespace Mzz\MzzBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class CookieResponseListener
{

    /**
     *
     * @param EventInterface $event    An EventInterface instance
     * @param Response       $response A Response instance
     */
    public function onCoreResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType())
            return $event;

        $request = $event->getRequest();
        $response = $event->getResponse();

        foreach ($request->getResponseCookies() as $cookie)
            $response->headers->setCookie($cookie);

        $event->setResponse($response);
        return $event;
    }

}

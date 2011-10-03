<?php

namespace Mzz\MzzBundle\Listener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\Container;
use Mzz\MzzBundle\Authentication\Providers\PreAuthenticatedAuthenticationProvider;
use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\RememberMeToken;
use Mzz\MzzBundle\Authentication\RememberMeAuthentication;
use Mzz\MzzBundle\Authentication\Exceptions\AuthenticationException;
use Mzz\MzzBundle\Authentication\RememberMeService;

class AuthRequestListener
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onCoreController(FilterControllerEvent $event, $value = null)
    {
        if ($this->isAlreadyAuthenticated())
            return $value;

        $session = $this->container->get('request')->getSession();
        $providerManager = $this->getAuthenticationProviderManager();

        try {
            $this->getSecurityContext()->setAuthentication($auth = $providerManager->authenticate($token = $this->getAuthenticationToken()));

            if ($auth instanceof RememberMeAuthentication && $auth->isAuthenticated()) {
                $token = $auth->getToken()->renewToken();
                $this->getUserRepository()->updateTokenForUser($auth->getPrincipal(), $token);
                $rm_service = new RememberMeService($this->container->get('request'));
                $rm_service->setRememberMeCookie($token);

                $session->set(PreAuthenticatedAuthenticationProvider::MZZ_USERID_SESSION_NAME, $auth->getPrincipalName());
            }
        } catch (AuthenticationException $e) {
            if ($e instanceof \Mzz\MzzBundle\Authentication\Exceptions\ThiefAssumedException)
            {
                $this->getUserRepository()->removeAllRememberMeTokensFor($token->getToken()->getPrincipal());
            }
            // in case of any error we destroy all wrong data and authenticate them as anonymous
            $this->destroyUserSessionAndCurrentRememberMeToken();
            $this->getSecurityContext()->setAuthentication($this->container->get('mzz.authentication.anonymous'));
        }

        return $value;
    }

    private function getSecurityContext()
    {
        return $this->container->get('mzz.authentication.security_context');
    }

    private function getUserRepository()
    {
        return $this->container->get('mzz.user_repository');
    }

    private function getAuthenticationProviderManager()
    {
        return $this->container->get('mzz.authentication_provider_manager');
    }

    private function getAuthenticationToken()
    {
        if ($user = $this->getPreAuthenticatedUsername()) {
            $auth_class = $this->container->getParameter('mzz.authentication.preauthenticated.class');
            return new $auth_class($user);
        }
        if ($token = $this->getRememberMeToken()) {
            $auth_class = $this->container->getParameter('mzz.authentication.rememberme.class');
            return new $auth_class($token->getPrincipal(), $token);
        }

        return $this->container->get('mzz.authentication.anonymous');
    }

    private function isAlreadyAuthenticated()
    {
        return $this->getSecurityContext()->getAuthentication() instanceof Authentication;
    }

    private function getPreAuthenticatedUsername()
    {
        $session = $this->container->get('request')->getSession();
        return $session->get(PreAuthenticatedAuthenticationProvider::MZZ_USERID_SESSION_NAME);
    }

    private function getRememberMeToken()
    {
        $token = $this->container->get('request')->cookies->get(\Mzz\MzzBundle\Authentication\RememberMeAuthentication::COOKIE_NAME);
        return RememberMeToken::createFromString(urldecode($token));
    }

    private function destroyUserSessionAndCurrentRememberMeToken()
    {
        $session = $this->container->get('request')->getSession();
        $session->remove(PreAuthenticatedAuthenticationProvider::MZZ_USERID_SESSION_NAME);

        $rm_service = new RememberMeService($this->container->get('request'));
        $rm_service->removeRememberMeCookie();
    }

}

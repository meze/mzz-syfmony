<?php

namespace Mzz\MzzBundle\Controller;

use Mzz\MzzBundle\Authentication\Exceptions\BadCredentialException;
use Mzz\MzzBundle\Authentication\Exceptions\UserNotFoundException;
use Mzz\MzzBundle\Authentication\UserRepository;
use Mzz\MzzBundle\Authentication\Providers\AuthenticationProviderManager;
use Mzz\MzzBundle\Authentication\Providers\PreAuthenticatedAuthenticationProvider;
use Mzz\MzzBundle\Authentication\SecurityContext;
use Mzz\MzzBundle\Authentication\Providers\AuthenticationProvider;
use Mzz\MzzBundle\Authentication\Authentication;
use Mzz\MzzBundle\Authentication\RememberMeAuthentication;
use Mzz\MzzBundle\Authentication\RememberMeService;
use Symfony\Component\HttpFoundation\Cookie;

abstract class UserController extends Controller
{
    protected $userRepository;
    protected $providerManager;
    protected $securityContext;
    protected $provider;
    protected $authenticationClassName;
    protected $defaultPageRouteName;
    protected $usernameFieldName = 'username';
    protected $passwordFieldName = 'password';

    const REMEMBER_ME_FIELD = 'remembered';

    public function __construct($request, AuthenticationProvider $provider, $authentication_class)
    {
        parent::__construct($request);
        $this->provider = $provider;
        $this->authenticationClassName = $authentication_class;
    }

    public function loginAction()
    {
        $user = $this->userRepository->createEmptyUser();

        $form = $this->container->get('form.factory')
            ->createBuilder('form')
            ->add('username', 'text')
            ->add('password', 'password')
            ->add('remembered', 'checkbox')
            ->getForm();

        if ($this->request->isPost()) {
            $data = $this->request->getArray($form->getName());
            $form->bindRequest($this->container->get('request'));

            if ($form->isValid()) {
                try {
                    $user = $this->userRepository->findByUsername($data[$this->usernameFieldName]);
                    $this->provider->setUser($user);
                    $this->provider->setSalt($user->getPasswordSalt());

                    $auth = $this->providerManager->authenticate($this->getAuthentication($data));
                    $this->securityContext->setAuthentication($auth);
                    $this->storeUserSession($user->getUsername());

                    if(isset($data[self::REMEMBER_ME_FIELD]) && $data[self::REMEMBER_ME_FIELD] == 1) {
                        $this->rememberUser($user);
                    }

                    return $this->redirectToOnlySameHost($this->getRedirectToUrl());
                }
                catch (BadCredentialException $e)
                {
                    $this->setBadCredentialsError($form);
                }
                catch (UserNotFoundException $e)
                {
                    $this->setBadCredentialsError($form);
                }
            }
        }

        return $this->renderLoginAction($form);
    }

    public function accessDeniedAction()
    {
        return $this->render('MzzBundle:User:access_denied.' . $this->templateExtension);
    }

    public function logoutAction()
    {
        $this->removeRememberMeToken();
        $this->destroySession();

        $rm_service = new RememberMeService($this->request);
        $rm_service->removeRememberMeCookie();

        $this->securityContext->setAuthentication(null);

        return $this->redirect($this->getHomePageUrl());
    }

    public function setUserRepository(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function setProviderManager(AuthenticationProviderManager $provider)
    {
        $this->providerManager = $provider;
    }

    public function setSecurityContext(SecurityContext $context)
    {
        $this->securityContext = $context;
    }

    public function setDefaultPageRouteName($name)
    {
        $this->defaultPageRouteName = $name;
    }

    protected function removeRememberMeToken()
    {
        $token = $this->container->get('request')->cookies->get(RememberMeAuthentication::COOKIE_NAME);
        $user = $this->securityContext->getAuthentication()->getPrincipalIdentity();
        $this->userRepository->removeRememberMeToken($user, urldecode($token));
    }

    protected function storeUserSession($username)
    {
        $this->request->getSession()->set(PreAuthenticatedAuthenticationProvider::MZZ_USERID_SESSION_NAME, $username);
    }

    protected function destroySession()
    {
        $this->request->getSession()->clear();
    }

    protected function redirectToOnlySameHost($url)
    {
        $trusted_url = $this->request->getScheme() . '://' . $this->request->getHost();
        if (substr($url, 0, \strlen($trusted_url)) !== $trusted_url)
            $url = $this->getHomePageUrl();
        return $this->redirect($url);
    }

    protected function getHomePageUrl()
    {
        if (empty ($this->defaultPageRouteName)) {
            throw new \RuntimeException("You should specify the name of a route for your default (main) page.");
        }
        return $this->url($this->defaultPageRouteName, array(), true);
    }

    protected function renderLoginAction($form)
    {
        return $this->render('MzzBundle:User:login.' . $this->templateExtension, array(
            'form' => $form->createView(),
            'redirectTo' => $this->getRedirectToUrl()));
    }

    protected function rememberUser($user)
    {
        $token = $this->userRepository->createNewRememberMeTokenFor($user);
        $rm_service = new RememberMeService($this->request);
        $rm_service->setRememberMeCookie($token);
    }

    private function getAuthentication($data)
    {
        // @todo replace with factory?
        $auth_class = $this->authenticationClassName;
        return new $auth_class($data[$this->usernameFieldName], $data[$this->passwordFieldName], array());
    }

    private function getRedirectToUrl()
    {
        $redirectTo = $this->request->getString('redirect_to');
        if (empty($redirectTo))
            $redirectTo = $this->request->server->get('HTTP_REFERER');
        if (empty($redirectTo))
            $redirectTo = $this->getHomePageUrl();
        return $redirectTo;
    }

    private function setBadCredentialsError($form)
    {
        $form->addError(new \Symfony\Component\Form\FormError("Bad username or password"));
    }

}

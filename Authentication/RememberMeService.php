<?php

namespace Mzz\MzzBundle\Authentication;
use Symfony\Component\HttpFoundation\Cookie;


class RememberMeService
{
    const COOKIE_LIFETIME = '30 days';

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function setRememberMeCookie($token)
    {
        $cookie = urlencode($token->__toString());

        $cookie = new Cookie(RememberMeAuthentication::COOKIE_NAME, $cookie, \strtotime('+' . self::COOKIE_LIFETIME), '/');
        $this->request->addCookie($cookie);
    }

    public function removeRememberMeCookie()
    {
        $this->request->addCookie(new Cookie(RememberMeAuthentication::COOKIE_NAME, '', \strtotime('-' . self::COOKIE_LIFETIME), '/'));
    }
}

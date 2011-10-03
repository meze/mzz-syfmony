<?php
namespace Mzz\MzzBundle\Request;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Cookie;

class Request extends SymfonyRequest
{
    protected $responseCookies = array();

    public function addCookie(Cookie $cookie)
    {
        $this->responseCookies[] = $cookie;
    }

    public function getResponseCookies()
    {
        return $this->responseCookies;
    }

    public function isPost()
    {
        return $this->getMethod() == 'POST';
    }

    public function getString($name, $default = null)
    {
        return $this->castValueOrDefaultToType(parent::get($name, $default), 'string', $default);
    }

    public function getInteger($name, $default = null)
    {
        return $this->castValueOrDefaultToType(parent::get($name, $default), 'integer', $default);
    }

    public function getNumeric($name, $default = null)
    {
        return $this->castValueOrDefaultToType(parent::get($name, $default), 'numeric', $default);
    }

    public function getArray($name, $default = null)
    {
        return $this->castValueOrDefaultToType(parent::get($name, $default), 'array', $default);
    }

    public function getBoolean($name, $default = null)
    {
        return $this->castValueOrDefaultToType(parent::get($name, $default), 'boolean', $default);
    }

    public function getBool($name, $default = null)
    {
        return $this->getBoolean($name, $default);
    }

    public function get($key, $default = null, $deep = false)
    {
        return parent::get($key, $default, $deep);
    }

    private function castValueOrDefaultToType($value, $type = 'mixed', $default = null)
    {
        $type = \strtolower($type);

        if (is_null($value))
            return (is_null($default) || empty($type) || $type == 'mixed') ? $default : $this->setType($default, $type);

        return (empty($type) || $type == 'mixed') ? $value : $this->setType($value, $type);
    }

    private function setType($value, $type)
    {
        if (is_array($value) && $type != 'array') {
            $value = array_shift($value);
            if (!is_scalar($value))
                $value = null;
        }

        if ($type == 'numeric')
            return 0 + $value;

        if (($type == 'boolean' || $type == 'bool') && \strtolower($value) == 'false')
            return false;

        if (!is_null($value) && gettype($value) != $type)
            settype($value, $type);

        return $value;
    }
}
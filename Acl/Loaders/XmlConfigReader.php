<?php

namespace Mzz\MzzBundle\Acl\Loaders;

class XmlConfigReader implements IConfigReader
{
    const ALL = '*';

    private $xml;
    private $path;

    public function __construct()
    {
    }

    public function getOptionForControllerAndMethod($option_name, $controller, $method)
    {
        $result = $this->processXmlConfig($option_name, $controller);
        $values = $result['values'] + $result['default_method'];

        if (\array_key_exists($method, $values))
            return $values[$method];
        if (\array_key_exists(self::ALL, $result['default_method']) && \is_null($result['default_controller']))
            return $result['default_method'][self::ALL];

        return (array)$result['default_controller'];
    }

    public function getOptionForController($option_name, $controller)
    {
        $result = $this->processXmlConfig($option_name, $controller);
        return $result['values'] + $result['default_method'];
    }

    public function load($path)
    {
        $this->path = $path;
        if (!\file_exists($this->path))
            throw new \RuntimeException('XML config for ACL roles is not found: ' . $this->path);
        $this->xml = simplexml_load_file($this->path);
    }

    private function explodeAndTrim($string)
    {
        return \array_map('trim', \explode(',', $string));
    }

    private function processXmlConfig($option_name, $controller)
    {
        $values = array();
        // defaults for controller may have an empty value, so we use null instead of an empty array
        $default_for_controller = null;
        $default_for_methods = array();

        foreach($this->xml->actions->action as $name => $action) {
            $attributes = $action->attributes();
            $isForCurrentController = (string)$attributes->controller == $controller;

            if ((string)$attributes->controller != self::ALL && !$isForCurrentController)
                continue;

            if (!isset($attributes->{$option_name})) {
                continue;
            }

            $attr = $attributes->{$option_name}->__toString();

            if ($option_name == 'roles') {
                $attr = $this->explodeAndTrim($attr);
            }

            if ((string)$attributes->controller == self::ALL)
                $default_for_methods[(string)$attributes->method] = $attr;

            if ((string)$attributes->method == self::ALL && $isForCurrentController)
                $default_for_controller = $attr;

            if ($attributes->method !== self::ALL && $isForCurrentController)
                $values[(string)$attributes->method] = $attr;
        }

        return array(
            'values' => $values,
            'default_controller' => $default_for_controller,
            'default_method' => $default_for_methods
        );
    }
}

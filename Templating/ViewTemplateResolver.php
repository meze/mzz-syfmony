<?php

namespace Mzz\MzzBundle\Templating;

class ViewTemplateResolver
{

    public static function resolve($controller, $class)
    {
        $action = preg_replace('/(.*?:|Action$)/', '', $controller);
        if (preg_match('~(\w+)\\\\(\w+Bundle).*?(\w+(?=Controller$))~', $class, $name)) {
            return implode(':', array($name[1] . $name[2], $name[3], $action));
        }
    }

}

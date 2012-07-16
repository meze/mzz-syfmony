<?php

namespace Mzz\MzzBundle\Templating;

class ViewTemplateResolver
{

    public static function resolve($controller, $class)
    {
        $action = preg_replace('/(.*?:|Action$)/', '', $controller);
        if (preg_match('~(\w+)\\\\(\w+Bundle).*?(\w+(?=Controller$))~', $class, $name)) {
            $action = preg_replace('~([A-Z])~e', '"_".strtolower("$1")', $action);
            return implode(':', array($name[1] . $name[2], $name[3], $action));
        }
    }

}

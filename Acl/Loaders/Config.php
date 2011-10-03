<?php

namespace Mzz\MzzBundle\Acl\Loaders;

use Mzz\MzzBundle\Acl\Loaders\XmlConfigReader;

class Config
{
    private $reader;

    public function __construct(IConfigReader $reader)
    {
        $this->reader = $reader;
    }

    public static function createFromXml($path)
    {
        $reader = new XmlConfigReader;
        $reader->load($path);
        return new self($reader);
    }

    public function getOptionForControllerAndMethod($option_name, $controller, $method)
    {
        return $this->reader->getOptionForControllerAndMethod($option_name, $controller, $method);
    }

    public function getOptionForController($option_name, $controller)
    {
        return $this->reader->getOptionForController($option_name, $controller);
    }
}

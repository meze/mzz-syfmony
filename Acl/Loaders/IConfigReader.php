<?php

namespace Mzz\MzzBundle\Acl\Loaders;

interface IConfigReader
{
    function getOptionForControllerAndMethod($option_name, $controller, $method);
    function getOptionForController($option_name, $controller);
}

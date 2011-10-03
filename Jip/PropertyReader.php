<?php

namespace Mzz\MzzBundle\Jip;

use Symfony\Component\Form\Exception\PropertyAccessDeniedException;
use Symfony\Component\Form\Exception\InvalidPropertyException;

/**
 * Symfony's forms has the thing we need here - reading an object's properties.
 * Unfortunately, it's not reusable because it's a part of the form class.
 *
 */
class PropertyReader
{
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function readProperties(array $names)
    {
        $values = array();
        foreach ($names as $key) {
            $values[$key] = $this->readProperty($key);
        }
        return $values;
    }

    public function readProperty($name)
    {
        $class = new \ReflectionClass(get_class($this->entity));
        $result = null;
        $name = \lcfirst($this->camelize($name));
        $getter = 'get' . ucfirst($name);

        if ($class->hasMethod($getter)) {
            if (!$class->getMethod($getter)->isPublic()) {
                throw new PropertyAccessDeniedException(sprintf('Method "%s()" is not public in class "%s"', $getter, $class->getName()));
            }

            $result = $this->entity->$getter();
        } else if ($class->hasProperty($name)) {
            if (!$class->getProperty($name)->isPublic()) {
                throw new PropertyAccessDeniedException(sprintf('Property "%s" is not public in class "%s". Maybe you should create the method "set%s()"?', $name, $class->getName(), ucfirst($name)));
            }

            $result = $this->entity->$name;
        } else if ($class->hasMethod('__get')) {
            $result = $this->entity->$name;
        } else {
            throw new InvalidPropertyException(sprintf('Neither property "%s" nor method "%s()" exists in class "%s"', $name, $getter, $class->getName()));
        }

        return $result;
    }

    protected function camelize($property)
    {
        return preg_replace('/(^|_)+(.)/e', "strtoupper('\\2')", $property);
    }

}

<?php

namespace Mzz\MzzBundle\Tests\Jip;
use Mzz\MzzBundle\Jip\PropertyReader;

class PropertyReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function readsPropertyOrCallGetterMethodToGetObjectProperties()
    {
        $entity = new EntityWithProperties;
        $reader = new PropertyReader($entity);

        $result = array(
            'title' => $entity->getTitle(),
            'name' => $entity->getName(),
            'author' => $entity->author
        );

        $this->assertEquals($result, $reader->readProperties(array(
            'title',
            'name',
            'author'
        )));

        $this->assertEquals($entity->getTitle(), $reader->readProperty('title'));
    }
}


class EntityWithProperties
{

    public $name = 'name_property';
    public $author = 'author';

    public function getTitle()
    {
        return 'title';
    }

    public function getName()
    {
        return 'name_method';
    }

}
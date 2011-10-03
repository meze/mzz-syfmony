<?php

namespace Mzz\MzzBundle\Tests\Jip\AnnotationReader;
use Mzz\MzzBundle\Jip\MenuNameAnnotationReader;

class MenuNameAnnotationReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function extractsJipMenuNameGivenInAnnotationFromObjectesAndClasses()
    {
        $reader1 = new MenuNameAnnotationReader(new Entity1);
        $reader2 = new MenuNameAnnotationReader(__NAMESPACE__ . "\\Entity2");

        $this->assertEquals("test", $reader1->getMenuName());
        $this->assertEquals("test2", $reader2->getMenuName());
    }

}


/**
 * @jip("test")
 */
class Entity1
{

}

/**
 *
 * Other comments
 *
 * @final
 * @jip( "test2" )
 * @orm:entity
 * @author me
 * @date 21.11.2011
 */
class Entity2
{

}

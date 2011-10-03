<?php

namespace Mzz\MzzBundle\Orm;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Tools\SchemaTool;
use Mzz\MzzBundle\Orm\MaterializedTree;

class MaterializedTreeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function makesOneLeafChildOfAnother()
    {
        /**
         * we'll build this simple tree: 4 -- 1-- 2 -- 3
         */
        $leaf1 = new TreeMenu(1);
        $this->assertEquals('1', $leaf1->getPath());
        $this->assertEquals(0, $leaf1->getLevel());

        $leaf2 = new TreeMenu(2);
        $leaf1->addChild($leaf2);
        $this->assertEquals('1/2', $leaf2->getPath());
        $this->assertEquals(1, $leaf2->getLevel());

        $leaf3 = new TreeMenu(3);
        $leaf2->addChild($leaf3);
        $this->assertEquals('1/2/3', $leaf3->getPath());
        $this->assertEquals(2, $leaf3->getLevel());

        $leaf4 = new TreeMenu(4);
        $leaf4->addChild($leaf3);
        $this->assertEquals('4/1/2/3', $leaf3->getPath());
        $this->assertEquals(3, $leaf3->getLevel());
    }

    /**
     * @test
     */
    public function parentIsSetAfterAddingChild()
    {
        $leaf1 = new TreeMenu(1);
        $this->assertEquals(0, $leaf1->getParent());

        $leaf2 = new TreeMenu(2);
        $leaf1->addChild($leaf2);
        $this->assertEquals(1, $leaf2->getParent());

        $leaf3 = new TreeMenu(3);
        $leaf2->addChild($leaf3);
        $this->assertEquals(2, $leaf3->getParent());

        $leaf4 = new TreeMenu(4);
        $leaf4->addChild($leaf3);
        $this->assertEquals(2, $leaf3->getParent());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throwsExceptionIfParentDoesNotHavePathWhenAddingChild()
    {
        $leaf = new TreeMenu(null);
        $leaf->addChild(new TreeMenu(1));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function throwsExceptionIfChildDoesNotHavePathWhenAddingToParent()
    {
        $leaf = new TreeMenu(1);
        $leaf->addChild(new TreeMenu(null));
    }
}

class TreeMenu extends MaterializedTree
{
    protected $id = 0;

    public function __construct($id)
    {
        $this->id = $id;
        $this->path = $id;
    }

    public function getIdentity()
    {
        return $this->id;
    }

}
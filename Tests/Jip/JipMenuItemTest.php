<?php

use Mzz\MzzBundle\Jip\JipMenuItem;


class JipMenuItemTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function titleIsNameWithFirstLetterCapitalizedIfNotSet()
    {
        $item = new JipMenuItem('test_name', 'test_router');
        $this->assertEquals('Test_name', $item->getTitle());

        $item = new JipMenuItem('test_name', 'test_router', 'Item title');
        $this->assertEquals('Item title', $item->getTitle());
    }

    /**
     * @test
     */
    public function itemMayHaveCustomHandler()
    {
        $item = new JipMenuItem('test_name', 'test_router');
        $this->assertFalse($item->hasHandler());

        $item->setHandler('remove');
        $this->assertTrue($item->hasHandler());

        $this->assertEquals('remove', $item->getHandler());
    }

}

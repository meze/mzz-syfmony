<?php

namespace Mzz\MzzBundle\Jip;

use Mzz\MzzBundle\Jip\JipMenuItem;

class JipMenu
{

    private $items;

    public function getItems()
    {
        return $this->items;
    }

    public function addItem($name, JipMenuItem $item)
    {
        $this->items[$name] = $item;
    }

    public function isEmpty()
    {
        return count($this->items) === 0;
    }

}

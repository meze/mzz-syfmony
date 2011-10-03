<?php

namespace Mzz\MzzBundle\Jip\Repository;

use Symfony\Component\Yaml\Yaml;
use Mzz\MzzBundle\Jip\JipMenuItem;

class YamlItemRepository implements ItemRepository
{

    private $items = array();

    public function __construct($path)
    {
        $this->items = $this->convertToObjects(Yaml::parse($path));
    }

    public function findAll()
    {
        return $this->items;
    }

    public function findByName($name)
    {
        return isset($this->items[$name]) ? $this->items[$name] : array();
    }

    /**
     * Converts the configuration into menu items objects
     *
     * @param array $items
     */
    private function convertToObjects(array $items)
    {
        $converted_items = array();
        $defaults = array('route' => '', 'title' => '', 'icon' => '');

        foreach ($items as $name => $item) {
            foreach ($item as $action => $config) {
                $config = array_merge($defaults, (array)$config);
                $converted_items[$name][$action] = new JipMenuItem($action, $config['route'], $config['title'], $config['icon']);

                if (isset($config['handler']))
                    $converted_items[$name][$action]->setHandler($config['handler']);
            }
        }

        return $converted_items;
    }

}

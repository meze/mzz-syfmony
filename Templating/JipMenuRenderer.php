<?php

namespace Mzz\MzzBundle\Templating;

class JipMenuRenderer
{
    private $provider;

    public function __construct($provider)
    {
        $this->provider = $provider;
    }

    public function show($entity, $parameters = array())
    {
        $menu = $this->provider->createFor($entity, $parameters);
        if ($menu->isEmpty())
            return;

        $items = $this->buildMenuItems($menu);

        // every menu on page must have an unique identificator
        $id =  htmlspecialchars(json_encode(is_object($entity) ? \spl_object_hash($entity) : $entity));

        return sprintf('<img src="/images/spacer.gif" class="jip jip-button" onmouseup="if (jipMenu) jipMenu.show(this, %s, %s, []);" alt="JIP Menu" height="10" width="20">',
            $id, \htmlspecialchars(\json_encode($items)));
    }

    protected function buildMenuItems($menu)
    {
        $items = array();
        foreach ($menu->getItems() as $item) {
            $_item = new JipMenuItem;
            $_item->title = $item->getTitle();
            $_item->url = $item->getUrl();
            $_item->icon = $item->getIcon();

            if ($item->hasHandler()) {
                $_item->handler = $item->getHandler();
            }

            $items[] = $_item->toArray();
        }
        return $items;
    }
}

<?php

namespace Mzz\MzzBundle\Jip;

class JipMenuItem
{

    private $title;
    private $icon;
    private $name;
    private $route;
    private $url;
    private $handler = '';

    public function __construct($name, $route_name, $title = '', $icon = '')
    {
        $this->name = $name;
        $this->route = $route_name;
        $this->title = $title;
        $this->icon = $icon;
    }

    public function getTitle()
    {
        return !empty($this->title) ? $this->title : ucfirst($this->name);
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    public function hasHandler()
    {
        return !empty($this->handler);
    }
}

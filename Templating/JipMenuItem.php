<?php

namespace Mzz\MzzBundle\Templating;

class JipMenuItem
{
    public $title = '';
    public $url = '';
    public $icon = '';
    public $isMultiLanguage = false;  // @todo multilanguage support is currently off for all items
    public $target = false; // @todo
    public $handler = '';

    public function toArray()
    {
        $array = array(
            $this->title,
            $this->url,
            $this->icon,
            $this->isMultiLanguage,
        );

        if ($this->handler) {
            $array[] = $this->target;
            $array[] = $this->handler;
        }
        
        return $array;
    }
}

<?php

namespace Mzz\MzzBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\HelperInterface;
use Mzz\MzzBundle\Templating\JipMenuRenderer;

class JipHelper implements HelperInterface
{
    protected $charset = 'UTF-8';
    private $renderer;

    public function __construct($provider)
    {
        $this->renderer = new JipMenuRenderer($provider);
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function menu($entity, $parameters = array())
    {
        return $this->renderer->show($entity, $parameters);
    }

    public function getName()
    {
        return 'jip';
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
}
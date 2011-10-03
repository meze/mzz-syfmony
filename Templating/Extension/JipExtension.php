<?php

namespace Mzz\MzzBundle\Templating\Extension;
use Mzz\MzzBundle\Templating\JipMenuRenderer;


class JipExtension extends \Twig_Extension
{
    private $renderer;

    public function __construct($provider)
    {
        $this->renderer = new JipMenuRenderer($provider);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'jip_menu' => new \Twig_Function_Method($this, 'menu', array('is_safe' => array('all'))),
        );
    }

    public function menu($entity, $parameters = array())
    {
        return $this->renderer->show($entity, $parameters);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'jip';
    }
}

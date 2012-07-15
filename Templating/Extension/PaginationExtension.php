<?php

namespace Mzz\MzzBundle\Templating\Extension;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Mzz\MzzBundle\Orm\Pagination;

class PaginationExtension extends \Twig_Extension
{
    private $environment;

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'pagination' => new \Twig_Function_Method($this, 'pagination', array('is_safe' => array('all'))),
        );
    }

    public function pagination(Pagination $pagination, $template = null, $additional_params = array(), $attrs = array())
    {
        if (null === $template) {
            $template = 'MzzBundle:Pagination:_standard.html.twig';
        }

        $template = $this->environment->loadTemplate($template);
        return $template->render(array(
            'pagination' => $pagination,
            'additional_params' => $additional_params,
            'attrs' => $attrs
        ));
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'pagination';
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
}

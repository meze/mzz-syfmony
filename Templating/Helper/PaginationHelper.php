<?php

namespace Mzz\MzzBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\HelperInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Mzz\MzzBundle\Orm\Pagination;

class PaginationHelper implements HelperInterface
{
    protected $charset = 'UTF-8';
    protected $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }


    public function getCharset()
    {
        return $this->charset;
    }

    public function getName()
    {
        return 'pagination';
    }

    public function display(Pagination $pagination, $template = null)
    {
        if (null === $template) {
            $template = 'MzzBundle:Pagination:_standard.html.php';
        }

        return $this->engine->render($template, array(
            'pagination' => $pagination
        ));
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
}
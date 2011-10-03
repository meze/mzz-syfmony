<?php

namespace Mzz\MzzBundle\Jip;

use Symfony\Component\Routing\RouterInterface;
use Mzz\MzzBundle\Jip\Repository\ItemRepository;
use Mzz\MzzBundle\Jip\MenuNameAnnotationReader;
use Mzz\MzzBundle\Jip\JipMenuItem;
use Mzz\MzzBundle\Jip\JipMenu;
use Mzz\MzzBundle\Jip\PropertyReader;

class MenuProvider
{
    private $generator;
    private $repository;
    private $router;

    public function __construct(RouterInterface $router, ItemRepository $repository)
    {
        $this->router = $router;
        $this->generator = $router->getGenerator();
        $this->repository = $repository;
    }

    public function createFor($object, array $parameters = array())
    {
        $reader = new MenuNameAnnotationReader($object);

        $items = $this->repository->findByName($reader->getMenuName());

        $menu = new JipMenu();
        foreach ($items as $name => $item) {
            $this->setUrl($object, $item, $parameters);
            $menu->addItem($name, $item);
        }

        return $menu;
    }

    public function setUrl($object, JipMenuItem $item, array $parameters)
    {
        $variables = $this->getRouteVariables($item->getRoute());
        $missing_keys = array_diff($variables, array_keys($parameters));

        $reader = new PropertyReader($object);
        $parameters = array_merge($parameters, $reader->readProperties($missing_keys));

        $values = array();
        foreach ($variables as $variable) {
            $values[$variable] = isset($parameters[$variable]) ? $parameters[$variable] : null;
        }

        $url = $this->generator->generate($item->getRoute(), $values, false);
        $item->setUrl($url);
    }

    private function getRouteVariables($route)
    {
        static $routes;
        if (empty($routes))
            $routes = $this->router->getRouteCollection();

        return $routes->get($route)->compile()->getVariables();
    }
}

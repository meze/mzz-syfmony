<?php

use Mzz\MzzBundle\Templating\ViewTemplateResolver;

class ViewTemplateResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function guessesNameUsingControllerClassNameAndRouterCallback()
    {
        $view = new ViewTemplateResolver();

        $controller = "tasks.team_controller:createAction";
        $class = "Application\TasksBundle\Controller\TeamController";

        $this->assertEquals("ApplicationTasksBundle:Team:create", ViewTemplateResolver::resolve($controller, $class));
    }

}

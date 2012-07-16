<?php

use Mzz\MzzBundle\Templating\ViewTemplateResolver;

class ViewTemplateResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function guessesNameUsingControllerClassNameAndRouterCallback()
    {
        $controller = "tasks.team_controller:createAction";
        $class = "Application\TasksBundle\Controller\TeamController";

        $this->assertEquals("ApplicationTasksBundle:Team:create", ViewTemplateResolver::resolve($controller, $class));
    }

    /**
     * @test
     */
    public function upperCaseLetterShouldBeConvertToLowerCaseWithUnderscore()
    {
        $controller = "tasks.team_controller:createNewsAction";
        $class = "Application\TasksBundle\Controller\TeamController";

        $this->assertEquals("ApplicationTasksBundle:Team:create_news", ViewTemplateResolver::resolve($controller, $class));
    }
}

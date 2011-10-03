<?php

use Symfony\Component\Yaml\Yaml;
use Mzz\MzzBundle\Jip\Repository\YamlItemRepository;
use Mzz\MzzBundle\Jip\JipMenuItem;

class YamlItemRepositoryTest extends \PHPUnit_Framework_TestCase
{

    private $parsedJipItems;

    // initialize values
    public function __construct()
    {
        $this->parsedJipItems = array(
          'news' =>
              array (
                'edit' => new JipMenuItem('edit', 'news_edit', 'Edit', 'edit.gif'),
                'delete' => $delete_news = new JipMenuItem('delete', 'news_delete', 'Delete news', 'delete.gif'),
              ),
          'page' =>
              array (
                'edit' => new JipMenuItem('edit', 'page_edit', 'Edit', 'edit.gif'),
                'create' => new JipMenuItem('create', 'page_create', 'Edit', 'edit.gif'),
                'delete' => $delete_page = new JipMenuItem('delete', 'page_delete', 'Delete', 'delete.gif'),
              ),
        );

        $delete_news->setHandler('Application.news.remove');
        $delete_page->setHandler('Application.page.remove');
    }

    /**
     * @test
     */
    public function findsAllJipMenuItems()
    {
        $repository = new YamlItemRepository(__DIR__ . '/../fixture/jip.yml');
        $this->assertEquals($this->parsedJipItems, $repository->findAll());
    }

    /**
     * @test
     */
    public function findsJipMenuItemByName()
    {
        $repository = new YamlItemRepository(__DIR__ . '/../fixture/jip.yml');
        $this->assertEquals($this->parsedJipItems['news'], $repository->findByName('news'));
        $this->assertEquals($this->parsedJipItems['page'], $repository->findByName('page'));
    }

    /**
     * @test
     */
    public function emptyResultIfItemWithSuchNameDoesNotExist()
    {
        $repository = new YamlItemRepository(__DIR__ . '/../fixture/jip.yml');
        $this->assertEquals(array(), $repository->findByName('not_present_in_config'));
    }

}

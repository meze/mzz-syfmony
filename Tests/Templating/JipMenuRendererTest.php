<?php

namespace Mzz\MzzBundle\Tests\Jip;
use Mzz\MzzBundle\Templating\JipMenuRenderer;

class JipMenuRendererTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function generatesHtmlCodeForDisplayingJipMenu()
    {
        $provider = $this->mockProvider();
        $provider->expects($this->once())
            ->method('createFor')
            ->will($this->returnValue($this->createMenu()));

        $jip = new JipMenuRenderer($provider);

        $entity = new Page();

        $script = 'if (jipMenu) jipMenu.show(this, "' . \spl_object_hash($entity) . '", ';
        $script .= '[["Move","\/page\/main\/edit","sprite:mzz-icon mzz-icon-sys mzz-icon-sys-page-edit",false],';
        $script .= '["Delete","\/page\/main\/delete","sprite:mzz-icon mzz-icon-sys mzz-icon-sys-page-del",false,false,"delete_page"]], []);';

        $html = '<img src="/images/spacer.gif" class="jip jip-button" onmouseup="';
        $html .= htmlspecialchars($script) . '" alt="JIP Menu" height="10" width="20">';


        $this->assertEquals($html, $jip->show($entity));
    }

    /**
     * @test
     */
    public function noHtmlIsGeneratedIfMenuHasNoItem()
    {
        $provider = $this->mockProvider();
        $provider->expects($this->once())
            ->method('createFor')
            ->will($this->returnValue($this->createEmptyMenu()));

        $jip = new JipMenuRenderer($provider);

        $this->assertEquals('', $jip->show(new Page));
    }

    private function createMenu()
    {
        $menu = new \Mzz\MzzBundle\Jip\JipMenu();
        $menu->addItem('move', $item = new \Mzz\MzzBundle\Jip\JipMenuItem('move', '', '', 'sprite:mzz-icon mzz-icon-sys mzz-icon-sys-page-edit'));
        $item->setUrl('/page/main/edit');

        $menu->addItem('delete', $item = new \Mzz\MzzBundle\Jip\JipMenuItem('delete', '', '', 'sprite:mzz-icon mzz-icon-sys mzz-icon-sys-page-del'));
        $item->setUrl('/page/main/delete');
        $item->setHandler('delete_page');

        return $menu;
    }


    private function createEmptyMenu()
    {
        return new \Mzz\MzzBundle\Jip\JipMenu();
    }

    private function mockProvider()
    {
        $router = $this->getMock("Symfony\Component\Routing\RouterInterface", array('getGenerator', 'match', 'generate', 'setContext'));
        $repository = $this->getMock("Mzz\MzzBundle\Jip\Repository\ItemRepository");
        return $this->getMock("Mzz\MzzBundle\Jip\MenuProvider", array(), array($router, $repository));
    }

}


/**
 *
 * @jip("page")
 */
class Page
{

}
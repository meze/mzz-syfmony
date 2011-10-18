<?php

use Mzz\MzzBundle\Response\JsonResponseMessage;

class JsonResponseMessageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function replyShouldBeJsonString()
    {
        $flash = new JsonResponseMessage();

        $this->assertEquals($flash->toJson(), '{"success":true,"data":""}');
    }

    /**
     * @test
     */
    public function replyShouldContainStatusAndMessage()
    {
        $flash = new JsonResponseMessage("Something is wrong", JsonResponseMessage::ERROR);
        $this->assertEquals($flash->toJson(), '{"success":false,"errors":"Something is wrong"}');

        $flash = new JsonResponseMessage("Everything is ok", JsonResponseMessage::OK);
        $this->assertEquals($flash->toJson(), '{"success":true,"data":"Everything is ok"}');
    }

}
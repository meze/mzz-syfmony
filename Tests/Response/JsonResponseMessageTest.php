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

        $this->assertEquals($flash->toJson(), '{"status":"ok","message":""}');
    }

    /**
     * @test
     */
    public function replyShouldContainStatusAndMessage()
    {
        $flash = new JsonResponseMessage("Something is wrong", JsonResponseMessage::ERROR);
        $this->assertEquals($flash->toJson(), '{"status":"error","message":"Something is wrong"}');

        $flash = new JsonResponseMessage("Everything is ok", JsonResponseMessage::OK);
        $this->assertEquals($flash->toJson(), '{"status":"ok","message":"Everything is ok"}');
    }

}

<?php

namespace Mzz\MzzBundle\Response;

class JsonResponseMessage
{
    const ERROR = "error";
    const OK = "ok";

    private $message;
    private $status;

    public function __construct($message = '', $status = self::OK)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public function toJson()
    {
        return \json_encode(array(
            'status' => $this->status,
            'message' => $this->message
        ));
    }

    public function __toString()
    {
        return $this->toJson();
    }
}

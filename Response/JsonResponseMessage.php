<?php

namespace Mzz\MzzBundle\Response;

class JsonResponseMessage
{
    const ERROR = false;
    const OK = true;

    private $data;
    private $success;

    public function __construct($data = '', $success = self::OK)
    {
        $this->data = $data;
        $this->success = $success;
    }

    public function toJson()
    {
        $response = array('success' => $this->success);

        if ($this->success == self::OK) {
            $response['data'] = $this->data;
        } else {
            $response['errors'] = $this->data;
        }
        return \json_encode($response);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}

<?php

namespace Routeless\Core\RPC;

class Response
{
    public $body = '';
    public $headers = [];

    public function header($key, $val)
    {
        $this->headers[$key] = $val;
    }

    public function put($data)
    {
        if (is_scalar($data)) {
            $this->body .= $data;
        } else {
            $this->body .= json_encode($data);
        }
    }

    public function send()
    {
        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }
        echo $this->body;
    }
}
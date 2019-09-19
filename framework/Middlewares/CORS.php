<?php


namespace Routeless\Middlewares;


use Routeless\Core\RPC\MiddleWare;
use Routeless\Core\RPC\Request;
use Routeless\Services\Cfg;

class CORS extends MiddleWare {
    public function handle() {
        $cfg = Cfg::get('app.cors') ?: [];
        $this->response->header('Access-Control-Allow-Origin', $cfg['origins'] ?? '*');
        $this->response->header('Access-Control-Allow-Headers', $cfg['headers'] ?? 'Authorization, Content-Type');
        $this->response->header('Access-Control-Allow-Methods', $cfg['methods'] ?? '*');
        if ($this->request->method() == Request::HTTP_METHOD_OPTIONS) {
            $this->response->send();
            die;
        }
    }
}


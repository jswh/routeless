<?php


namespace Routeless\Middlewares;


use Routeless\Core\RPC\MiddleWare;

class ApplicationJson extends MiddleWare {
    public function handle() {
        $this->response->header('Content-Type', 'application/json');
    }
}
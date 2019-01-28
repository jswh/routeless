<?php


namespace Routeless\Core\RPC;


abstract class MiddleWare {
    public $request, $response;
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }
    abstract public function handle();
}
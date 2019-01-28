<?php
namespace Core\Exceptions;



use Core\RPC\Request;
use Core\RPC\Response;

class ErrorHandler
{
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function handle(\Throwable $e) {
        $this->response->put(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        $this->response->send();
        die;
    }
}
<?php
namespace Core\RPC;

use Core\Exceptions\HttpException;

class Process
{
    public $class;
    public $method;

    public function __construct($class, $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    public function run(Request $request, Response $response)
    {
        $class = $this->class;
        if (!class_exists($class)) {
            throw new HttpException(404, $class);
        }
        $instance = new $class($request, $response);

        $args = $this->buildArgs($instance, $request->input());

        return call_user_func_array([$instance, $this->method], $args);
    }

    private function buildArgs($instance, $input) {
        try {
            $params = (new \ReflectionMethod($instance, $this->method))->getParameters();
        } catch (\ReflectionException $e) {
            throw new HttpException(404, 'method not found');
        }
        $callPars = [];
        foreach ($params as $p) {
            $key = $p->getName();

            if (isset($input[$key])) {
                $callPars[] = $input[$key];
            } elseif ($key == 'otherArgs') {
                $callPars[] = $input;
            } elseif ($p->isDefaultValueAvailable()) {
                $callPars[] = $p->getDefaultValue();
            } else {
                throw new HttpException(400, "params missing : $key");
            }

            unset($input[$key]);
        }

        return $callPars;
    }
}
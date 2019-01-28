<?php

namespace Core\RPC;

use Core\Exceptions\HttpException;
use function Couchbase\defaultDecoder;

class RPC
{
    protected $map = [];
    public function __construct($map = [])
    {
        $this->map = $map;
    }

    public function handle(Request $request, Response $response) {
        list($prefix, $class, $method) = $this->parsePath($request->path());

        $mapConfig = $this->getExecuteConfig($prefix);

        $namespace = trim($mapConfig['namespace'], '\\');
        $this->processMiddleWare($mapConfig['before'] ?? [], $request, $response);
        $rtn = (new Process($namespace . '\\' . $class, $method))->run($request, $response);

        if (!($rtn instanceof Response)) {
            $response->put($rtn);
        }

        $this->processMiddleWare($mapConfig['after'] ?? [], $request, $response);

        return $response;
    }

    private function processMiddleWare($middleWares, $request, $response) {
        foreach ($middleWares as $mid) {
            (new $mid($request, $response))->handle();
        }
    }

    private function parsePath($path) {
        $match = preg_match('/([a-zA-Z0-9:\/]*\/)([a-zA-z]+)\.([a-zA-Z]+)/', $path, $parts);
        if (!$match || count($parts) != 4 ) {
            throw new HttpException(404, 'no path match');
        }

        return [$parts[1], dash2Camel($parts[2]), lcfirst(dash2Camel($parts[3]))];
    }

    private function getExecuteConfig($prefix) {
        $mapConfig = $this->map[$prefix] ?? null;
        if (!$mapConfig) {
            throw new HttpException(404, 'no execute config');
        }
        return $mapConfig;
    }
}
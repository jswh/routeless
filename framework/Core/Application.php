<?php

namespace Routeless\Core;


use Routeless\Core\Exceptions\ErrorHandler;
use Routeless\Core\RPC\{Request, Response, RPC};

class Application
{
    /** @var static $app */
    protected static $app;
    public $config;

    public function __construct($configPath)
    {
        $this->config = new Config($configPath);
        static::$app = $this;
    }

    public static function config()
    {
        return static::$app->config;
    }

    public function exec()
    {
        $request = Request::capture();
        $response = new Response();
        $response->header('Content-Type', 'application/json');
        try {
            DB::boot($this->config->get('database.mysql'));

            (new RPC($this->config->get('route')))->handle($request, $response);

            $response->send();
        } catch (\Throwable $e) {
            (new ErrorHandler($request, $response))->handle($e);
        }
    }
}
<?php


namespace Routeless\Services;


use Routeless\Core\Application;
use \Redis as RedisDriver;

class Redis {
    /** @var RedisDriver $client */
    protected static $client;
    public static function get() {
        if (!static::$client) {
            static::$client = new RedisDriver();
            $cfg = Application::config()->get('database.redis');
            static::$client->pconnect($cfg['host'], $cfg['port']);
        }
        return self::$client;
    }
}
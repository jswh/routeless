<?php


namespace Routeless\Services;


use \Redis as RedisDriver;

class Redis
{
    /** @var RedisDriver $client */
    protected static $client;
    protected static $config;

    public static function boot($config)
    {
        self::$config = $config;
    }

    public static function get() {
        if (!static::$client) {
            static::$client = new RedisDriver();
            static::$client->pconnect($config['host'], $config['port']);
            if ($config['secret']) {
                self::$client->auth($config['secret']);
            }
        }
        return self::$client;
    }
}

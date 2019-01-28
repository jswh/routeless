<?php


namespace Routeless\Services;


use \Redis as RedisDriver;

class Redis
{
    /** @var RedisDriver $client */
    protected static $client;

    public static function boot($config)
    {
        if (!static::$client) {
            static::$client = new RedisDriver();
            static::$client->pconnect($config['host'], $config['port']);
            if ($config['secret']) {
                self::$client->auth($config['secret']);
            }
        }
        return self::$client;
    }

    public static function get() {
        return self::$client;
    }
}
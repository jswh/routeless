<?php


namespace Core;


use Illuminate\Database\Capsule\Manager;

class DB extends Manager {
    public static function boot($config) {
        $db = new static();
        $db->addConnection($config);
        $db->setAsGlobal();
    }
}
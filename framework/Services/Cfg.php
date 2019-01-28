<?php


namespace Routeless\Services;


use Routeless\Core\Application;

class Cfg {
    protected function __construct(Application $app) {
        $this->app = $app;
    }

    /** @var static $instance  */
    protected static $instance;
    public static function init($app) {
        if (!isset(self::$instance)) {
            self::$instance = new static($app);
        }
        return self::$instance;
    }

    public static function get($cfg) {
        return self::$instance->getConfig($cfg);
    }

    public function getConfig($cfg) {
        return $this->app->config->get($cfg);
    }
}
<?php

use PHPUnit\Framework\TestCase;
use Routeless\Core\Config;

class ConfigTest extends TestCase {

    public static $config = null;
    public static function setUpBeforeClass() : void {
        $path = realpath(__DIR__ . '/config');
        self::$config = new Config($path);
    }


    public function testGet() {
        $cfg = self::$config;
        $this->assertEquals($cfg->get('not_exist'), null);
        $this->assertEquals($cfg->get('not_exist.sub'), null);
        $this->expectException(\Exception::class);
        $cfg->get('empty');
        $this->assertEquals($cfg->get('db.not_exist'), null);
        $this->assertArrayHasKey('mysql', $cfg->get('db'));
        $this->assertArrayHasKey('host', $cfg->get('db.mysql'));
        $this->assertEquals($cfg->get('db.mysql.host'), '127.0.0.1');
    }
}

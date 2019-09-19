<?php

use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase {
    public function testHello()
    {
        $this->assertEquals("Hello " . "World", "Hello World");
    }

}

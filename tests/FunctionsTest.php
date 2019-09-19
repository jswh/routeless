<?php

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase {
    public function testStr2Camel()
    {
        $testers = [
            'a' => 'A',
            'aa' => 'Aa',
            'AA' => 'Aa',
            'aA' => 'Aa',
            'a-a' => 'A-a',
            'A-a' => 'A-a',
        ];
        foreach($testers as $tester => $result) {
            $this->assertEquals(str2Camel($tester), $result);
        }
    }

    public function testDash2Camel()
    {
        $testers = [
            'a' => 'A',
            'aa' => 'Aa',
            'AA' => 'Aa',
            'aA' => 'Aa',
            'a-a' => 'AA',
            'A-a' => 'AA',
            'aa-aa' => 'AaAa',
            'Aa-aa' => 'AaAa',
            'aA-aa' => 'AaAa',
            'aa-Aa' => 'AaAa',
            'aa-aA' => 'AaAa',
            'AA-aa' => 'AaAa',
            'Aa-Aa' => 'AaAa',
            'Aa-aA' => 'AaAa',
            'AA-aa' => 'AaAa',
            'AA-Aa' => 'AaAa',
            'AA-aA' => 'AaAa',
            'AA-Aa' => 'AaAa',
            'AA-AA' => 'AaAa',
        ];
        foreach($testers as $tester => $result) {
            $this->assertEquals(dash2Camel($tester), $result);
        }
    }

    public function testPublicMembers() {
        $testObject = "str";
        $this->assertEmpty(publicMembers($testObject));
        $testObject = 1;
        $this->assertEmpty(publicMembers($testObject));
        $testObject = false;
        $this->assertEmpty(publicMembers($testObject));
        $stdClassObj = new stdClass();
        $this->assertEmpty(publicMembers($testObject));
        $stdClassObj->key = 1;
        $stdClassObj->key_two = 'str';
        $arr = publicMembers($stdClassObj);
        $this->assertContains('key', $arr);
        $this->assertContains('key_two', $arr);
        $classObj = new TestClass();
        $arr = publicMembers($classObj);
        $this->assertNotContains('key', $arr);
        $this->assertNotContains('keys', $arr);
        $this->assertNotContains('key2', $arr);
        $this->assertNotContains('keys2', $arr);
        $this->assertContains('key3', $arr);
        $this->assertNotContains('keys3', $arr);
        $this->assertNotContains('con', $arr);
    }


    public function testObj2Array() {
        // test not object
        $testObject = "str";
        $this->assertEmpty(obj2Array($testObject));
        $testObject = 1;
        $this->assertEmpty(obj2Array($testObject));
        $testObject = false;
        $this->assertEmpty(obj2Array($testObject));
        //test stdClass object
        $stdClassObj = new stdClass();
        $this->assertEmpty(obj2Array($testObject));
        $stdClassObj->key = 1;
        $stdClassObj->key_two = 'str';
        $arr = obj2Array($stdClassObj);
        $this->assertArrayHasKey('key', $arr);
        $this->assertEquals($arr['key'], 1);
        $this->assertArrayHasKey('key_two', $arr);
        $this->assertEquals($arr['key_two'], 'str');
        //test normal class object
        $classObj = new TestClass();
        $arr = obj2Array($classObj);
        $this->assertArrayNotHasKey('key', $arr);
        $this->assertArrayNotHasKey('keys', $arr);
        $this->assertArrayNotHasKey('key2', $arr);
        $this->assertArrayNotHasKey('keys2', $arr);
        $this->assertArrayHasKey('key3', $arr);
        $this->assertEquals($arr['key3'], 'b');
        $this->assertArrayNotHasKey('keys3', $arr);
        $this->assertArrayNotHasKey('con', $arr);
    }

    public function testRandStr() {
        $askii = randStr(5);
        $this->assertEquals(strlen($askii), 5);
        $utf8 = randStr(5, '只要去製作一個奪取(爭奪)差事然後名稱.說明.相機隨便亂邊就好了');
        $this->assertEquals(mb_strlen($utf8), 5);
    }
}

class TestClass {
    private $key = 'a';
    private static $keys = 'a';
    protected $key2 = 'a';
    protected static $keys2 = 'a';
    public $key3 = 'b';
    public static $keys3 = 'b';
    const con = 'c';
}

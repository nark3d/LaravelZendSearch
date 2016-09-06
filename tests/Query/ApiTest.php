<?php

namespace BestServedCold\LaravelZendSearch\Query;

use BestServedCold\LaravelZendSearch\TestCase;

class ApiTest extends TestCase
{
    public function testSomething()
    {
        $this->assertTrue(true);
    }
//    public function testKeyword()
//    {
//        $api = new Api;
//        $result = $api->keyword('bob');
//        $this->assertSame('bob', $api->getKeyword());
//        $this->assertInstanceOf(Api::class, $result);
//    }
//
//    public function testField()
//    {
//        $api = new Api;
//        $result = $api->field('bob');
//        $this->assertSame('bob', $api->getField());
//        $this->assertInstanceOf(Api::class, $result);
//    }
//
//    public function testFuzzy()
//    {
//        $api = new Api;
//        $result = $api->fuzzy(1);
//        $this->assertSame(1, $api->getFuzziness());
//        $this->assertInstanceOf(Api::class, $result);
//    }
//
//    public function testPhrase()
//    {
//        $api = new Api;
//        $result = $api->phrase(1);
//        $this->assertSame(1, $api->getPhrase());
//        $this->assertInstanceOf(Api::class, $result);
//    }
//
//    public function testWildcard()
//    {
//        $api = new Api;
//        $result = $api->wildcard();
//        $this->assertSame(true, $api->isWildcard());
//        $this->assertInstanceOf(Api::class, $result);
//    }
//
//    public function testOption()
//    {
//        $api = new Api;
//
//        $result = $api->required();
//        $this->assertSame(true, $api->getOption());
//        $this->assertInstanceOf(Api::class, $result);
//
//        $result = $api->optional();
//        $this->assertSame(null, $api->getOption());
//        $this->assertInstanceOf(Api::class, $result);
//
//        $result = $api->prohibited();
//        $this->assertSame(false, $api->getOption());
//        $this->assertInstanceOf(Api::class, $result);
//    }
//
//    public function testQuery()
//    {
//        $api = new Api;
//        $array = ['bob', 'mary'];
//
//        $api->keyword($array);
//        $result = $api->query();
//        var_dump(get_class($result));
//
//    }
    
}

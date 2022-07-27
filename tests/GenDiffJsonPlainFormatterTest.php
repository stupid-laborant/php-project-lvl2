<?php

use PHPUnit\Framework\TestCase;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

class GenDiffJsonPlainFormatterTest extends TestCase
{

    public function testTwoValidJsons()
    {
        $expected = <<<DOC
Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to null
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From '' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]
DOC;
        $this->assertEquals($expected, genDiff(__DIR__ . "/mock/nonflat1.json", __DIR__ . "/mock/nonflat2.json", 'plain'));

    }

    public function testTwoSimpleJsons()
    {
        $expected = <<<DOC
Property 'key.first' was updated. From 'one' to 1
Property 'key.zero' was removed
DOC;

        $this->assertEquals($expected, genDiff(__DIR__ . "/mock/bothJsonArrayValue1.json", __DIR__ . "/mock/bothJsonArrayValue2.json", 'plain'));
    }

}
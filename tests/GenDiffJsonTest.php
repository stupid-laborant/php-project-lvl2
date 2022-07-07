<?php

use PHPUnit\Framework\TestCase;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

class GenDiffJsonTest extends TestCase
{
    public function testOnlyFirstJsonValue()
    {
        $expected = <<<DOC
{
  - key1: {
        key: value
    }
    key2: value2
}
DOC;
        $firstFile = __DIR__ . '/mock/onlyFirstJsonValue1.json';
        $secondFile = __DIR__ . '/mock/onlyFirstJsonValue2.json';
        $this->assertEquals($expected, genDiff($firstFile, $secondFile));
    }

    public function testBothJsonArrayValue()
    {
        $expected = <<<DOC
{
    doesnt: matter
    key: {
      - first: one
      + first: 1
        second: two
      - zero: zero
    }
}
DOC;
        $firstFile = __DIR__ . '/mock/bothJsonArrayValue1.json';
        $secondFile = __DIR__ . '/mock/bothJsonArrayValue2.json';
        $this->assertEquals($expected, genDiff($firstFile, $secondFile));
    }

    public function testFirstJsonArraySecondNot()
    {
        $expected = <<<DOC
{
    doesnt: matter
  - key: {
        first: one
        second: two
        zero: zero
    }
  + key: value
}
DOC;
        $firstFile = __DIR__ . '/mock/firstArrayJsonSecondNot1.json';
        $secondFile = __DIR__ . '/mock/firstArrayJsonSecondNot2.json';
        $this->assertEquals($expected, genDiff($firstFile, $secondFile));
    }

    public function testTwoFlatJsons()
    {
        $expected = <<<DOC
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
DOC;
        $firstFile = __DIR__ . '/mock/file1.json';
        $secondFile = __DIR__ . '/mock/file2.json';
        $this->assertEquals($expected, genDiff($firstFile, $secondFile));
    }

    public function testEmptyFile()
    {
        $expected = <<<DOC
{
}
DOC;
        $emptyFile = __DIR__ . '/mock/empty.json';
        $this->assertEquals($expected, genDiff($emptyFile, $emptyFile));
    }

    public function testJsonArrayStructureLikeDiff()
    {
        $expected = <<<DOC
{
    doesnt: matter
    key: {
        is_leaf: true
        new_value: value2
        old_value: value1
    }
}
DOC;
        $fileWithDiffStructure = __DIR__ . '/mock/JsonArrayStructureLikeDiff.json';
        $this->assertEquals($expected, genDiff($fileWithDiffStructure, $fileWithDiffStructure));
    }

    public function testTwoValidJsons()
    {
        $expected = <<<DOC
{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: null
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow:
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        deep: {
            id: {
                number: 45
            }
        }
        fee: 100500
    }
}
DOC;
        $this->assertEquals($expected, genDiff(__DIR__ . "/mock/nonflat1.json", __DIR__ . "/mock/nonflat2.json"));
    }
}

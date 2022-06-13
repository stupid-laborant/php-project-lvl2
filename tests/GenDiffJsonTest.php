<?php

namespace Hexlet\Code\GenDiff\Test;

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
    private string $validJsonFile1;
    private string $validJsonFile2;
    private string $emptyFile;
    private string $validJsonFileAbsolut;
    private string $invalidJsonFile;
    private string $expectedResult;

    public function setUp(): void
    {
        $this->validJsonFile1 = __DIR__ . "/mock/file1.json";
        $this->validJsonFile2 = __DIR__ . "/mock/file2.json";
        $this->emptyFile = __DIR__ . "/mock/empty.json";
        $this->validJsonFileAbsolut = "tests/mock/file2.json";
        $this->invalidJsonFile = __DIR__ . "/mock/invalid.json";
        $this->expectedResult = <<<DOC
{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}
DOC;
    }

    public function testTwoValidJson()
    {
        $this->assertEquals($this->expectedResult, genDiff($this->validJsonFile1, $this->validJsonFile2));
    }

    public function testTwoJsonRelativePath()
    {
        $this->assertEquals($this->expectedResult, genDiff($this->validJsonFile1, $this->validJsonFileAbsolut));
    }

    public function testEmptyJsonFirst()
    {
        $expected = <<<DOC
{
  + host: hexlet.io
  + timeout: 20
  + verbose: true
}
DOC;
        $this->assertEquals($expected, genDiff($this->emptyFile, $this->validJsonFile2));
    }

    public function testEmptyJsonSecond()
    {
        $expected = <<<DOC
{
  - follow: false
  - host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
}
DOC;
        $this->assertEquals($expected, genDiff($this->validJsonFile1, $this->emptyFile));
    }

    public function testInvalidJson()
    {
        $this->expectError();
        genDiff($this->validJsonFile1, $this->invalidJsonFile);
    }
}

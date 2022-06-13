<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../vendor/autoload.php';



class GenDiffYamlTest extends TestCase
{

    private string $validYamlFile1;
    private string $validYamlFile2;
    private string $emptyYamlFile;
    private string $expectedResult;

    public function setUp(): void
    {
        $this->validYamlFile1 = __DIR__ . "/mock/file1.yml";
        $this->validYamlFile2 = __DIR__ . "/mock/file2.yml";
        $this->emptyYamlFile = __DIR__ . "/mock/empty.yml";
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

    public function testTwoValidYaml()
    {
        $this->assertEquals($this->expectedResult, genDiff($this->validYamlFile1, $this->validYamlFile2));
    }

    public function testEmptyYamlFirst()
    {
        $expected = <<<DOC
{
  + host: hexlet.io
  + timeout: 20
  + verbose: true
}
DOC;
        $this->assertEquals($expected, genDiff($this->emptyYamlFile, $this->validYamlFile2));
    }

    public function testEmptyYamlLast()
    {
        $expected = <<<DOC
{
  - follow: false
  - host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
}
DOC;
        $this->assertEquals($expected, genDiff($this->validYamlFile1, $this->emptyYamlFile));
    }
}

<?php

namespace Differ\GenDiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function dataProviderGenDiff(): array
    {
        return [
            [
                $this->getFullPath("/fixtures/json/nonflat1.json"),
                $this->getFullPath("/fixtures/json/nonflat2.json")
            ],
            [
                $this->getFullPath("/fixtures/yml/nonflat1.yml"),
                $this->getFullPath("/fixtures/yml/nonflat2.yml"),

            ]
        ];
    }
    private function getFullPath(string $path): string
    {
        return __DIR__ . $path;
    }
    /**
     * @dataProvider dataProviderGenDiff
     */
    public function testGenDiffDefault($file1, $file2,)
    {
        $this->assertEquals(
            file_get_contents($this->getFullPath("/fixtures/diff.stylish")),
            genDiff($file1, $file2)
        );
    }
    /**
     * @dataProvider dataProviderGenDiff
     */
    public function testGenDiffStylish($file1, $file2,)
    {
        $this->assertEquals(
            file_get_contents($this->getFullPath("/fixtures/diff.stylish")),
            genDiff($file1, $file2, 'stylish')
        );
    }
    /**
     * @dataProvider dataProviderGenDiff
     */
    public function testGenDiffPlain($file1, $file2,)
    {
        $this->assertEquals(
            file_get_contents($this->getFullPath("/fixtures/diff.plain")),
            genDiff($file1, $file2, 'plain')
        );
    }
    /**
     * @dataProvider dataProviderGenDiff
     */
    public function testGenDiffJson($file1, $file2,)
    {
        $this->assertEquals(
            file_get_contents($this->getFullPath("/fixtures/diff.json")),
            genDiff($file1, $file2, 'json')
        );
    }
}

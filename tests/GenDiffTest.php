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
                $this->getFullPath("/fixtures/json/nonflat2.json"),
                file_get_contents($this->getFullPath("/fixtures/diff.stylish")),
                "stylish"
            ],
            [
                $this->getFullPath("/fixtures/json/nonflat1.json"),
                $this->getFullPath("/fixtures/json/nonflat2.json"),
                file_get_contents($this->getFullPath("/fixtures/diff.plain")),
                "plain"
            ],
            [
                $this->getFullPath("/fixtures/json/nonflat1.json"),
                $this->getFullPath("/fixtures/json/nonflat2.json"),
                file_get_contents($this->getFullPath("/fixtures/diff.json")),
                "json"
            ],
            [
                $this->getFullPath("/fixtures/yml/nonflat1.yml"),
                $this->getFullPath("/fixtures/yml/nonflat2.yml"),
                file_get_contents($this->getFullPath("/fixtures/diff.stylish")),
                "stylish"
            ],
            [
                $this->getFullPath("/fixtures/yml/nonflat1.yml"),
                $this->getFullPath("/fixtures/yml/nonflat2.yml"),
                file_get_contents($this->getFullPath("/fixtures/diff.plain")),
                "plain"
            ],
            [
                $this->getFullPath("/fixtures/yml/nonflat1.yml"),
                $this->getFullPath("/fixtures/yml/nonflat2.yml"),
                file_get_contents($this->getFullPath("/fixtures/diff.json")),
                "json"
            ],
        ];
    }
    private function getFullPath(string $path): string
    {
        return __DIR__ . $path;
    }

    /**
     * @dataProvider dataProviderGenDiff
     */
    public function testGenDiff($file1, $file2, $expected, $formatter)
    {
        $this->assertEquals($expected, genDiff($file1, $file2, $formatter));
    }
}

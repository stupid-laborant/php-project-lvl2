<?php

namespace Differ\GenDiffTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    public function dataProviderStylish(): array
    {
        return [
            [
                __DIR__ . "/fixtures/json/file1.json",
                __DIR__ . "/fixtures/json/file2.json",
                file_get_contents(__DIR__ . "/fixtures/stylish/flatJson")
            ],
            [
                __DIR__ . "/fixtures/yml/file1.yml",
                __DIR__ . "/fixtures/yml/file2.yml",
                file_get_contents(__DIR__ . "/fixtures/stylish/flatJson")
            ],
            [
                __DIR__ . "/fixtures/json/nonflat1.json",
                __DIR__ . "/fixtures/json/nonflat2.json",
                file_get_contents(__DIR__ . "/fixtures/stylish/nonflatJson")
            ],
            [
                __DIR__ . "/fixtures/yml/nonflat1.yml",
                __DIR__ . "/fixtures/yml/nonflat2.yml",
                file_get_contents(__DIR__ . "/fixtures/stylish/nonflatJson")
            ],
            [
                __DIR__ . "/fixtures/json/deleteEditMultiplyNested1.json",
                __DIR__ . "/fixtures//json/deleteEditMultiplyNested2.json",
                file_get_contents(__DIR__ . "/fixtures/stylish/deleteEditMultiplyNested")
            ],
            [
                __DIR__ . "/fixtures/json/deleteEditNonflat1.json",
                __DIR__ . "/fixtures/json/deleteEditNonflat2.json",
                file_get_contents(__DIR__ . "/fixtures/stylish/deleteEditNonflat")
            ],
            [
                __DIR__ . "/fixtures/json/empty.json",
                __DIR__ . "/fixtures/json/empty.json",
                "{" . PHP_EOL . "}"
            ]
            ];
    }

    public function dataProviderPlain(): array
    {
        return [
            [
                __DIR__ . "/fixtures/json/file1.json",
                __DIR__ . "/fixtures/json/file2.json",
                file_get_contents(__DIR__ . "/fixtures/plain/flatJson")
            ],
            [
                __DIR__ . "/fixtures/yml/file1.yml",
                __DIR__ . "/fixtures/yml/file2.yml",
                file_get_contents(__DIR__ . "/fixtures/plain/flatJson")
            ],
            [
                __DIR__ . "/fixtures/json/nonflat1.json",
                __DIR__ . "/fixtures/json/nonflat2.json",
                file_get_contents(__DIR__ . "/fixtures/plain/nonflatJson")
            ],
            [
                __DIR__ . "/fixtures/yml/nonflat1.yml",
                __DIR__ . "/fixtures/yml/nonflat2.yml",
                file_get_contents(__DIR__ . "/fixtures/plain/nonflatJson")
            ],
            [
                __DIR__ . "/fixtures/json/deleteEditMultiplyNested1.json",
                __DIR__ . "/fixtures//json/deleteEditMultiplyNested2.json",
                file_get_contents(__DIR__ . "/fixtures/plain/deleteEditMultiplyNested")
            ],
            [
                __DIR__ . "/fixtures/json/deleteEditNonflat1.json",
                __DIR__ . "/fixtures/json/deleteEditNonflat2.json",
                file_get_contents(__DIR__ . "/fixtures/plain/deleteEditNonflat")
            ],
            [
                __DIR__ . "/fixtures/json/empty.json",
                __DIR__ . "/fixtures/json/empty.json",
                ""
            ]
        ];
    }

    public function dataProviderJson(): array
    {
        return [
            [
                __DIR__ . "/fixtures/json/file1.json",
                __DIR__ . "/fixtures/json/file2.json",
                file_get_contents(__DIR__ . "/fixtures/jsonFormatter/flatJson")
            ],
            [
                __DIR__ . "/fixtures/yml/file1.yml",
                __DIR__ . "/fixtures/yml/file2.yml",
                file_get_contents(__DIR__ . "/fixtures/jsonFormatter/flatJson")
            ],
            [
                __DIR__ . "/fixtures/json/nonflat1.json",
                __DIR__ . "/fixtures/json/nonflat2.json",
                file_get_contents(__DIR__ . "/fixtures/jsonFormatter/nonflatJson")
            ],
            [
                __DIR__ . "/fixtures/yml/nonflat1.yml",
                __DIR__ . "/fixtures/yml/nonflat2.yml",
                file_get_contents(__DIR__ . "/fixtures/jsonFormatter/nonflatJson")
            ],
            [
                __DIR__ . "/fixtures/json/deleteEditMultiplyNested1.json",
                __DIR__ . "/fixtures//json/deleteEditMultiplyNested2.json",
                file_get_contents(__DIR__ . "/fixtures/jsonFormatter/deleteEditMultiplyNested")
            ],
            [
                __DIR__ . "/fixtures/json/deleteEditNonflat1.json",
                __DIR__ . "/fixtures/json/deleteEditNonflat2.json",
                file_get_contents(__DIR__ . "/fixtures/jsonFormatter/deleteEditNonflat")
            ],
            [
                __DIR__ . "/fixtures/json/empty.json",
                __DIR__ . "/fixtures/json/empty.json",
                "[]"
            ]
        ];
    }

    /**
     * @dataProvider dataProviderStylish
     */
    public function testDefaultFormatter($file1, $file2, $expected)
    {
        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    /**
     * @dataProvider dataProviderStylish
     */
    public function testStylishFormatter($file1, $file2, $expected)
    {
        $this->assertEquals($expected, genDiff($file1, $file2, 'stylish'));
    }

    /**
     * @dataProvider dataProviderPlain
     */
    public function testPlainFormatter($file1, $file2, $expected)
    {
        $this->assertEquals($expected, genDiff($file1, $file2, 'plain'));
    }

    /**
     * @dataProvider dataProviderJson
     */
    public function testJsonFormatter($file1, $file2, $expected)
    {
        $this->assertEquals($expected, genDiff($file1, $file2, 'json'));
    }
}

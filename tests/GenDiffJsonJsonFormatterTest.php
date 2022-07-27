<?php

use PHPUnit\Framework\TestCase;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

class GenDiffJsonJsonFormatterTest extends TestCase
{
    public function testOnlyFirstJsonValue()
    {
        $expected = <<<DOC
{
  {
    "key": "key1"
    "value": {
      "key": "value"
    }
    "flag": "removed"
  }
  {
    "key": "key2"
    "value": "value2"
    "flag": "unchanged"
  }
}
DOC;
        $firstFile = __DIR__ . '/mock/onlyFirstJsonValue1.json';
        $secondFile = __DIR__ . '/mock/onlyFirstJsonValue2.json';
        $this->assertEquals($expected, genDiff($firstFile, $secondFile, 'json'));
    }

    public function testBothJsonArrayValue()
    {
        $expected = <<<DOC
{
  {
    "key": "doesnt"
    "children": {
      {
        "key": "matter"
        "value": "matter"
        "flag": "unchanged"
      }
      {
        "key": "mean"
        "value": "mean"
        "flag": "unchanged"
      }
    }
    "flag": "complex_value"
  }
  {
    "key": "key"
    "children": {
      {
        "key": "first"
        "old_value": "one"
        "new_value": 1
        "flag": "updated"
      }
      {
        "key": "second"
        "value": "two"
        "flag": "unchanged"
      }
      {
        "key": "zero"
        "value": "zero"
        "flag": "removed"
      }
    }
    "flag": "complex_value"
  }
}
DOC;
        $firstFile = __DIR__ . '/mock/bothJsonArrayValue1.json';
        $secondFile = __DIR__ . '/mock/bothJsonArrayValue2.json';
        $this->assertEquals($expected, genDiff($firstFile, $secondFile, 'json'));
    }

    function testTwoValidJsons()
    {
        $expected = <<<DOC
{
  {
    "key": "common"
    "children": {
      {
        "key": "follow"
        "value": false
        "flag": "added"
      }
      {
        "key": "setting1"
        "value": "Value 1"
        "flag": "unchanged"
      }
      {
        "key": "setting2"
        "value": 200
        "flag": "removed"
      }
      {
        "key": "setting3"
        "old_value": true
        "new_value": null
        "flag": "updated"
      }
      {
        "key": "setting4"
        "value": "blah blah"
        "flag": "added"
      }
      {
        "key": "setting5"
        "value": {
          "key5": "value5"
        }
        "flag": "added"
      }
      {
        "key": "setting6"
        "children": {
          {
            "key": "doge"
            "children": {
              {
                "key": "wow"
                "old_value": ""
                "new_value": "so much"
                "flag": "updated"
              }
            }
            "flag": "complex_value"
          }
          {
            "key": "key"
            "value": "value"
            "flag": "unchanged"
          }
          {
            "key": "ops"
            "value": "vops"
            "flag": "added"
          }
        }
        "flag": "complex_value"
      }
    }
    "flag": "complex_value"
  }
  {
    "key": "group1"
    "children": {
      {
        "key": "baz"
        "old_value": "bas"
        "new_value": "bars"
        "flag": "updated"
      }
      {
        "key": "foo"
        "value": "bar"
        "flag": "unchanged"
      }
      {
        "key": "nest"
        "old_value": {
          "key": "value"
        }
        "new_value": "str"
        "flag": "updated"
      }
    }
    "flag": "complex_value"
  }
  {
    "key": "group2"
    "value": {
      "abc": 12345
      "deep": {
        "id": 45
      }
    }
    "flag": "removed"
  }
  {
    "key": "group3"
    "value": {
      "deep": {
        "id": {
          "number": 45
        }
      }
      "fee": 100500
    }
    "flag": "added"
  }
}
DOC;
        $this->assertEquals($expected, genDiff(__DIR__ . "/mock/nonflat1.json", __DIR__ . "/mock/nonflat2.json", "json"));
    }
}

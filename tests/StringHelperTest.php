<?php
namespace Hgraca\Helper\Test;

use Hgraca\Helper\StringHelper;
use PHPUnit_Framework_TestCase;

final class StringHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider_testIsEmpty
     */
    public function testIsEmpty($string, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::isEmpty($string));
    }

    public function dataProvider_testIsEmpty(): array
    {
        return [
            ['', true],
            ['non empty string', false],
        ];
    }

    /**
     * @dataProvider dataProvider_testHas
     */
    public function testHas($needle, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::has($needle, $haystack));
    }

    public function dataProvider_testHas(): array
    {
        return [
            ['', 'beginning to ending', true],
            ['beginning', 'beginning to ending', true],
            ['to', 'beginning to ending', true],
            ['ending', 'beginning to ending', true],
            ['unexistent', 'beginning to ending', false],
        ];
    }

    /**
     * @dataProvider dataProvider_testHasBeginning
     */
    public function testHasBeginning($needle, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::hasBeginning($needle, $haystack));
    }

    public function dataProvider_testHasBeginning(): array
    {
        return [
            ['', 'beginning to ending', true],
            ['beginning', 'beginning to ending', true],
            ['to', 'beginning to ending', false],
            ['ending', 'beginning to ending', false],
            ['unexistent', 'beginning to ending', false],
        ];
    }

    /**
     * @dataProvider dataProvider_testHasEnding
     */
    public function testHasEnding($needle, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::hasEnding($needle, $haystack));
    }

    public function dataProvider_testHasEnding(): array
    {
        return [
            ['', 'beginning to ending', true],
            ['beginning', 'beginning to ending', false],
            ['to', 'beginning to ending', false],
            ['ending', 'beginning to ending', true],
            ['unexistent', 'beginning to ending', false],
        ];
    }

    /**
     * @dataProvider dataProvider_testToCamel
     */
    public function testToCamel($string, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::toCamel($string));
    }

    public function dataProvider_testToCamel(): array
    {
        return [
            ['camel-from beginning_to ending', 'camelFromBeginningToEnding'],
        ];
    }

    /**
     * @dataProvider dataProvider_testToSnake
     */
    public function testToSnake($string, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::toSnake($string));
    }

    public function dataProvider_testToSnake(): array
    {
        return [
            ['CamelFromBeginningToEnding', 'camel_from_beginning_to_ending'],
            ['camelFromBeginningToEnding', 'camel_from_beginning_to_ending'],
            ['camel-from beginning_to ending', 'camel_from_beginning_to_ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testToStudly
     */
    public function testToStudly($string, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::toStudly($string));
    }

    public function dataProvider_testToStudly(): array
    {
        return [
            ['camel-from beginning_to ending', 'CamelFromBeginningToEnding'],
        ];
    }

    /**
     * @dataProvider dataProvider_testIsJson
     */
    public function testIsJson($string, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::isJson($string));
    }

    public function dataProvider_testIsJson(): array
    {
        return [
            ['camel-from beginning_to ending', false],
            [json_encode(['test' => 1, 'a' => 'blabla']), true],
        ];
    }

    /**
     * @dataProvider dataProvider_testRemove
     */
    public function testRemove($needle, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::remove($needle, $haystack));
    }

    public function dataProvider_testRemove(): array
    {
        return [
            ['', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'beginning to ending', ' to ending'],
            ['to', 'beginning to ending', 'beginning  ending'],
            ['to', 'beginning to to endtoing', 'beginning   ending'],
            ['ending', 'beginning to ending', 'beginning to '],
            ['unexistent', 'beginning to ending', 'beginning to ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testRemoveFromBeginning
     */
    public function testRemoveFromBeginning($needle, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::removeFromBeginning($needle, $haystack));
    }

    public function dataProvider_testRemoveFromBeginning(): array
    {
        return [
            ['', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'beginning to ending', ' to ending'],
            ['to', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'beginningbeginning to ending', 'beginning to ending'],
            ['ending', 'beginning to ending', 'beginning to ending'],
            ['unexistent', 'beginning to ending', 'beginning to ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testRemoveFromEnd
     */
    public function testRemoveFromEnd($needle, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::removeFromEnd($needle, $haystack));
    }

    public function dataProvider_testRemoveFromEnd(): array
    {
        return [
            ['', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'beginning to ending', 'beginning to ending'],
            ['to', 'beginning to ending', 'beginning to ending'],
            ['ending', 'beginning to endingending', 'beginning to ending'],
            ['ending', 'beginning to ending', 'beginning to '],
            ['unexistent', 'beginning to ending', 'beginning to ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testReplace
     */
    public function testReplace($needle, $replace, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::replace($needle, $replace, $haystack));
    }

    public function dataProvider_testReplace(): array
    {
        return [
            ['', 'A', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'AAA', 'beginning to ending', 'AAA to ending'],
            ['to', 'AAA', 'beginning to ending', 'beginning AAA ending'],
            ['ending', 'AAA', 'beginning to endingending', 'beginning to AAAAAA'],
            ['ending', 'AAA', 'beginning to ending', 'beginning to AAA'],
            ['unexistent', 'AAA', 'beginning to ending', 'beginning to ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testReplaceFromBeginning
     */
    public function testReplaceFromBeginning($needle, $replace, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::replaceFromBeginning($needle, $replace, $haystack));
    }

    public function dataProvider_testReplaceFromBeginning(): array
    {
        return [
            ['', 'A', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'AAA', 'beginning to ending', 'AAA to ending'],
            ['beginning', 'AAA', 'beginningbeginning to ending', 'AAAbeginning to ending'],
            ['to', 'AAA', 'beginning to ending', 'beginning to ending'],
            ['ending', 'AAA', 'beginning to endingending', 'beginning to endingending'],
            ['ending', 'AAA', 'beginning to ending', 'beginning to ending'],
            ['unexistent', 'AAA', 'beginning to ending', 'beginning to ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testReplaceFromEnd
     */
    public function testReplaceFromEnd($needle, $replace, $haystack, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::replaceFromEnd($needle, $replace, $haystack));
    }

    public function dataProvider_testReplaceFromEnd(): array
    {
        return [
            ['', 'A', 'beginning to ending', 'beginning to ending'],
            ['beginning', 'AAA', 'beginning to ending', 'beginning to ending'],
            ['to', 'AAA', 'beginning to ending', 'beginning to ending'],
            ['ending', 'AAA', 'beginning to endingending', 'beginning to endingAAA'],
            ['ending', 'AAA', 'beginning to ending', 'beginning to AAA'],
            ['unexistent', 'AAA', 'beginning to ending', 'beginning to ending'],
        ];
    }

    /**
     * @dataProvider dataProvider_testToSlug
     */
    public function testToSlug($string, $resultWithDashes, $inputIsEnglishOnly, $expectedResult)
    {
        self::assertEquals($expectedResult, StringHelper::toSlug($string, $resultWithDashes, $inputIsEnglishOnly));
    }

    public function dataProvider_testToSlug(): array
    {
        return [
            ['`~#this_is_%%a!{(title)}[]_of<>_p@o&$st*;,.\/-~!', true, true, 'this-is-a-title-of-post'],
            ['`~#this_is_%%a!{(title)}[]_of<>_p@o&$st*;,.\/-~!', false, true, 'this is a title of post'],
            ['`~#this_is_%%a!{(title)}[]_of<>_p@o&$st*;,.\/-~!', true, false, 'this-is-a-title-of-post'],
            ['`~#this_is_%%a!{(title)}[]_of<>_p@o&$st*;,.\/-~!', false, false, 'this is a title of post'],
        ];
    }
}

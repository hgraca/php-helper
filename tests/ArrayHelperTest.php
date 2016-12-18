<?php

namespace Hgraca\Helper\Test;

use Hgraca\Helper\ArrayHelper;
use PHPUnit_Framework_TestCase;

final class ArrayHelperTest extends PHPUnit_Framework_TestCase
{
    /** @var array */
    private static $testArray = [
        'base' => ['path' => ['with' => 'data']],
        'multiple' => [
            'path' => [
                'with' => [
                    'multiple',
                    'data',
                ],
            ],
        ],
        'another' => 'path',
    ];

    public function testRenameKeys()
    {
        $expected = [
            'base' => 'one',
            'another' => 'two',
        ];
        $notExpected = [
            'not_found' => 'tree',
        ];

        $result = ArrayHelper::renameKeys(
            self::$testArray,
            $mapper = array_merge(
                $expected,
                $notExpected
            )
        );

        foreach (array_values($expected) as $value) {
            self::assertArrayHasKey($value, $result);
        }

        foreach (array_values($notExpected) as $value) {
            self::assertArrayNotHasKey($value, $result);
        }
    }

    public function testExtract_WhenPathDoesNotExist()
    {
        self::assertSame(ArrayHelper::extract(self::$testArray, 'non.existent.path'), null);
    }

    public function testExtract_WithSingleValueAtEnd()
    {
        self::assertSame(ArrayHelper::extract(self::$testArray, 'base.path.with'), 'data');
    }

    public function testExtract_WithIncompletePath()
    {
        self::assertSame(
            [
                'multiple',
                'data',
            ],
            ArrayHelper::extract(self::$testArray, 'multiple.path.with')
        );
    }

    public function testExtract_WithEmptyPath()
    {
        self::assertSame(self::$testArray, ArrayHelper::extract(self::$testArray, ''));
    }

    /**
     * @test
     *
     * @small
     *
     * @dataProvider dataProvider_isTwoDimensional
     */
    public function isTwoDimensional(array $array, bool $expectedResult)
    {
        self::assertEquals($expectedResult, ArrayHelper::isTwoDimensional($array));
    }

    public function dataProvider_isTwoDimensional(): array
    {
        return [
            [[[], []], true],
            [[[1, 2, 3], [1, 2, 3]], true],
            [[], false],
            [[1, 2, 3], false],
        ];
    }

    /**
     * @test
     *
     * @small
     */
    public function mapRecursive_WithSingleDimensionArray()
    {
        $testArray = [1, 2, 3];

        self::assertSame(
            [2, 3, 4],
            ArrayHelper::mapRecursive(
                function ($value) {
                    return $value + 1;
                },
                $testArray
            )
        );
    }

    /**
     * @test
     *
     * @small
     */
    public function mapRecursive_WithMultiDimensionArray()
    {
        $testArray = [1, 2, [3, 4, 5]];

        self::assertSame(
            [2, 3, [4, 5, 6]],
            ArrayHelper::mapRecursive(
                function ($value) {
                    return $value + 1;
                },
                $testArray
            )
        );
    }
}

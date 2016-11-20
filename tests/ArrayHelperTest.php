<?php
namespace Hgraca\Helper\Test;

use Hgraca\Helper\ArrayHelper;
use PHPUnit_Framework_TestCase;

final class ArrayHelperTest extends PHPUnit_Framework_TestCase
{
    /** @var array */
    private static $testArray = [
        'base'     => ['path' => ['with' => 'data']],
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
            'base'    => 'one',
            'another' => 'two',
        ];
        $notExpected = [
            'not_found' => 'tree'
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
            ArrayHelper::extract(self::$testArray, 'multiple.path.with'),
            [
                'multiple',
                'data',
            ]
        );
    }

    public function testExtract_WithEmptyPath()
    {
        self::assertSame(self::$testArray, ArrayHelper::extract(self::$testArray, ''));
    }
}

<?php

namespace Hgraca\Helper\Test;

use Hgraca\Helper\JsonHelper;
use PHPUnit_Framework_TestCase;

final class JsonHelperTest extends PHPUnit_Framework_TestCase
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

    public function testToArray_WithValidString()
    {
        self::assertEquals(self::$testArray, JsonHelper::toArray(json_encode(self::$testArray)));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testToArray_WithInvalidString()
    {
        JsonHelper::toArray('invalid json string');
    }
}

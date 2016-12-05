<?php

namespace Hgraca\Helper\Test;

use Hgraca\Helper\EntityHelper;
use Hgraca\Helper\Test\Stub\AEntity;
use Hgraca\Helper\Test\Stub\BEntity;
use PHPUnit_Framework_TestCase;

final class EntityHelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider_testFromArray
     */
    public function testFromArray($classOrObject, array $array, $expectedObject)
    {
        self::assertEquals($expectedObject, EntityHelper::fromArray($classOrObject, $array));
    }

    public function dataProvider_testFromArray(): array
    {
        $bEntity = new BEntity();
        $bEntity->setPropertyA('B');
        $bEntity->setPropertyB(2);
        $bEntity->setPropertyC(['B', 2]);

        $aEntity = new AEntity();
        $aEntity->setPropertyA('A');
        $aEntity->setPropertyB(1);
        $aEntity->setPropertyC(['A', 1]);
        $aEntity::setPropertyD($bEntity);

        $cEntity = new AEntity();
        $cEntity->setPropertyA('A');
        $cEntity->setPropertyB(1);

        $dEntity = new AEntity();
        $dEntity->setPropertyA('A');
        $dEntity->setPropertyB(2);
        $dEntity->setPropertyC(['C', 3]);

        return [
            'full filling' => [
                AEntity::class,
                [
                    'propertyA' => 'A',
                    'propertyB' => 1,
                    'propertyC' => ['A', 1],
                    'propertyD' => $bEntity,
                ],
                $aEntity,
            ],
            'partial filling' => [
                AEntity::class,
                [
                    'propertyA' => 'A',
                    'propertyB' => 1,
                ],
                $cEntity,
            ],
            'partial filling replacing some data' => [
                $cEntity,
                [
                    'propertyB' => 2,
                    'propertyC' => ['C', 3],
                ],
                $dEntity,
            ],
        ];
    }

    /**
     * @dataProvider dataProvider_testToArray
     */
    public function testToArray(array $mapper, array $expectedArray)
    {
        $bEntity = new BEntity();
        $bEntity->setPropertyA('B');
        $bEntity->setPropertyB(2);
        $bEntity->setPropertyC(['B', 2]);

        $aEntity = new AEntity();
        $aEntity->setPropertyA('A');
        $aEntity->setPropertyB(1);
        $aEntity->setPropertyC(['A', 1]);
        $aEntity::setPropertyD($bEntity);

        self::assertEquals($expectedArray, EntityHelper::toArray($aEntity, $mapper));
    }

    public function dataProvider_testToArray(): array
    {
        return [
            [
                [],
                [
                    'propertyA' => 'A',
                    'propertyB' => 1,
                    'propertyC' => ['A', 1],
                    'propertyD' => [
                        'propertyA' => 'B',
                        'propertyB' => 2,
                        'propertyC' => ['B', 2],
                    ],
                ],
            ],
            [
                [
                    'propertyA' => ['name' => 'A_pA'],
                    'propertyB' => ['name' => 'A_pB'],
                    'propertyC' => ['name' => 'A_pC', 'mapper' => [0 => 'zero', 1 => 'one']],
                    'propertyD' => [
                        'name' => 'A_pD',
                        'mapper' => [
                            'propertyA' => ['name' => 'A_pD_A'],
                            'propertyB' => ['name' => 'A_pD_B'],
                            'propertyC' => ['name' => 'A_pD_C', 'mapper' => [0 => 1, 1 => 2]],
                        ],
                    ],
                ],
                [
                    'A_pA' => 'A',
                    'A_pB' => 1,
                    'A_pC' => ['zero' => 'A', 'one' => 1],
                    'A_pD' => [
                        'A_pD_A' => 'B',
                        'A_pD_B' => 2,
                        'A_pD_C' => [1 => 'B', 2 => 2],
                    ],
                ],
            ],
        ];
    }
}

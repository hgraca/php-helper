<?php
namespace Hgraca\Helper\Test;

use Hgraca\Helper\ClassHelper;
use Hgraca\Helper\Test\Stub\AEntity;
use Hgraca\Helper\Test\Stub\BEntity;
use PHPUnit_Framework_TestCase;

final class ClassHelperTest extends PHPUnit_Framework_TestCase
{
    public function testExtractCanonicalClassName()
    {
        self::assertEquals('AEntity', ClassHelper::extractCanonicalClassName(AEntity::class));
    }

    public function testFindConstants()
    {
        self::assertEquals(
            [
                AEntity::CONSTANT_A => AEntity::CONSTANT_A,
                AEntity::CONSTANT_B => AEntity::CONSTANT_B,
            ],
            ClassHelper::findConstants(AEntity::class))
        ;
    }

    /**
     * @dataProvider dataProvider_testFindPropertiesNames
     */
    public function testFindPropertiesNames(array $expectedPropertyList, array $namesFilter, array $visibilityFilter)
    {
        self::assertEquals(
            $expectedPropertyList,
            ClassHelper::findPropertiesNames(AEntity::class, $namesFilter, $visibilityFilter)
        );
    }

    public function dataProvider_testFindPropertiesNames(): array
    {
        return [
            [['propertyA', 'propertyB', 'propertyC', 'propertyD'], [], []],
            [['propertyA', 'propertyD'], ['propertyB', 'propertyC'], []],
            [['propertyA'], ['propertyB', 'propertyC'], ['static']],
            [['propertyA', 'propertyC'], [], ['protected', 'static']],
        ];
    }

    /**
     * @dataProvider dataProvider_testHasMethod
     */
    public function testHasMethod(string $methodName, bool $expectedResult)
    {
        self::assertEquals($expectedResult, ClassHelper::hasMethod(AEntity::class, $methodName));
    }

    public function dataProvider_testHasMethod(): array
    {
        return [
            ['methodA', true],
            ['methodB', true],
            ['methodC', true],
            ['methodD', true],
            ['unexistentMethod', false],
        ];
    }

    /**
     * @dataProvider dataProvider_testGetParameters
     */
    public function testGetParameters($methodName, $expectedResult)
    {
        self::assertEquals($expectedResult, ClassHelper::getParameters(AEntity::class, $methodName));
    }

    public function dataProvider_testGetParameters(): array
    {
        return [
            [
                'methodA',
                [
                    ['name' => 'parameterA'],
                    ['name' => 'parameterB'],
                    ['name' => 'parameterC', 'class' => BEntity::class]
                ]
            ],
            ['methodB', []],
        ];
    }

    public function testGetReflectionProperties()
    {
        $expectedPropertyNameList = ['propertyA', 'propertyB', 'propertyC', 'propertyD'];
        $reflectionPropertyList = ClassHelper::getReflectionProperties(AEntity::class);

        foreach ($reflectionPropertyList as $key => $reflectionProperty) {
            self::assertEquals($expectedPropertyNameList[$key], $reflectionProperty->getName());
        }
    }

    public function testSetReflectionPropertiesVisible()
    {
        $reflectionPropertyList   = ClassHelper::getReflectionProperties(AEntity::class);
        ClassHelper::setReflectionPropertiesAccessible($reflectionPropertyList);

        $entity = new AEntity();

        foreach ($reflectionPropertyList as $key => $reflectionProperty) {
            $reflectionProperty->getValue($entity);
        }
    }
}

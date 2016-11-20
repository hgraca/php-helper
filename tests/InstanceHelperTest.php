<?php
namespace Hgraca\Helper\Test;

use Hgraca\Helper\EntityHelper;
use Hgraca\Helper\InstanceHelper;
use Hgraca\Helper\Test\Stub\AEntity;
use Hgraca\Helper\Test\Stub\CEntity;
use PHPUnit_Framework_TestCase;

final class InstanceHelperTest extends PHPUnit_Framework_TestCase
{

    public function testGetReflectionProperties()
    {
        $expectedPropertyNameList = ['propertyA', 'propertyB', 'propertyC', 'propertyD'];
        $reflectionPropertyList   = InstanceHelper::getReflectionProperties(new AEntity());

        foreach ($reflectionPropertyList as $key => $reflectionProperty) {
            self::assertEquals($expectedPropertyNameList[$key], $reflectionProperty->getName());
        }
    }

    public function testCreateInstance()
    {
        $cEntity = InstanceHelper::createInstance(CEntity::class);

        $expectedArray = [
            'propertyA' => null,
            'propertyB' => null,
            'propertyC' => [],
        ];

        self::assertEquals($expectedArray, EntityHelper::toArray($cEntity));
    }

    public function testSetProtectedProperty()
    {
        $propertyValue = 'AAA';
        $cEntity       = new CEntity($propertyValue);
        self::assertEquals($propertyValue, $cEntity->getPropertyA());

        $propertyValue = 'BBB';
        InstanceHelper::setProtectedProperty($cEntity, 'propertyA', $propertyValue);
        self::assertEquals($propertyValue, $cEntity->getPropertyA());
    }

    public function testSetProtectedProperty_DefinedInParentClass()
    {
        $propertyValue = 'ZZZ';
        $cEntity       = new CEntity();
        $cEntity->setPropertyZ($propertyValue);
        self::assertEquals($propertyValue, $cEntity->getPropertyZ());

        $propertyValue = 'BBB';
        InstanceHelper::setProtectedProperty($cEntity, 'propertyZ', $propertyValue);
        self::assertEquals($propertyValue, $cEntity->getPropertyZ());
    }

    /**
     * @expectedException \ReflectionException
     * @expectedExceptionMessage Property fooBar does not exist
     */
    public function testSetProtectedProperty_FailsWhenCantFindTheProperty()
    {
        $cEntity = new CEntity();
        InstanceHelper::setProtectedProperty($cEntity, 'fooBar', 'non existent');
    }


    public function testGetProtectedProperty()
    {
        $cEntity = new CEntity();

        $propertyA = InstanceHelper::getProtectedProperty($cEntity, 'propertyA');
        self::assertEquals($cEntity->getPropertyA(), $propertyA);
    }

    public function testGetProtectedProperty_DefinedInParentClass()
    {
        $propertyValue = 'ZZZ';
        $cEntity       = new CEntity();
        $cEntity->setPropertyZ($propertyValue);

        $propertyZ = InstanceHelper::getProtectedProperty($cEntity, 'propertyZ');
        self::assertEquals($cEntity->getPropertyZ(), $propertyZ);
    }

    /**
     * @expectedException \ReflectionException
     * @expectedExceptionMessage Property fooBar does not exist
     */
    public function testGetProtectedProperty_FailsWhenCantFindTheProperty()
    {
        $cEntity = new CEntity();
        InstanceHelper::getProtectedProperty($cEntity, 'fooBar');
    }
}

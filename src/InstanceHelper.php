<?php

namespace Hgraca\Helper;

use Hgraca\Helper\Concept\ReflectionHelperAbstract;
use ReflectionProperty;

final class InstanceHelper extends ReflectionHelperAbstract
{
    /**
     * @param mixed $object
     *
     * @return ReflectionProperty[]
     */
    public static function getReflectionProperties($object): array
    {
        return ClassHelper::getReflectionProperties(get_class($object));
    }

    /**
     * @return mixed
     */
    public static function createInstance(string $fqcn, array $constructorArguments = [])
    {
        $reflectionClass = self::getReflectionClass($fqcn);

        return $reflectionClass->newInstanceArgs($constructorArguments);
    }

    /**
     * @return mixed
     */
    public static function createInstanceWithoutConstructor(string $fqcn)
    {
        $reflectionClass = self::getReflectionClass($fqcn);

        return $reflectionClass->newInstanceWithoutConstructor();
    }

    public static function setProtectedProperty($object, string $propertyName, $value)
    {
        $class = self::getReflectionClass(get_class($object));

        $property = ClassHelper::getReflectionProperty($class, $propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    public static function getProtectedProperty($object, string $propertyName)
    {
        $class = self::getReflectionClass(get_class($object));

        $property = ClassHelper::getReflectionProperty($class, $propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}

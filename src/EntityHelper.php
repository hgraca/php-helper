<?php

namespace Hgraca\Helper;

use Hgraca\Helper\Concept\HelperAbstract;
use ReflectionProperty;

final class EntityHelper extends HelperAbstract
{
    /**
     * @param object|string $entity
     * @param array        $array
     *
     * @return object
     */
    public static function fromArray($entity, array $array)
    {
        if (is_string($entity)) {
            $entity = InstanceHelper::createInstanceWithoutConstructor($entity);
        }

        $reflectionPropertyList = InstanceHelper::getReflectionProperties($entity);
        ClassHelper::setReflectionPropertiesAccessible($reflectionPropertyList);

        /** @var ReflectionProperty $reflectionProperty */
        foreach ($reflectionPropertyList as $reflectionProperty) {
            $propertyName = $reflectionProperty->getName();
            if (array_key_exists($propertyName, $array)) {
                $reflectionProperty->setValue($entity, $array[$propertyName]);
            }
        }

        return $entity;
    }

    /**
     * @param mixed|object|array $data An object, array or native value
     * @param array              $propertyNameMapper
     *
     * @return array
     */
    public static function toArray($data, array $propertyNameMapper = []): array
    {
        if (is_object($data)) {
            return self::entityToArray($data, $propertyNameMapper);
        }

        if (is_array($data)) {
            return self::arrayToArray($data, $propertyNameMapper);
        }

        return [$data];
    }

    private static function entityToArray($entity, array $propertyNameMapper): array
    {
        $array = [];

        $propertyList = is_string($entity)
            ? ClassHelper::getReflectionProperties($entity)
            : InstanceHelper::getReflectionProperties($entity);

        foreach ($propertyList as $property) {
            $propertyName = $property->getName();
            $property->setAccessible(true);
            $propertyValue = $property->getValue($entity);

            $array = self::addToResultArray($propertyName, $propertyValue, $propertyNameMapper, $array);
        }

        return $array;
    }

    private static function arrayToArray(array $data, array $propertyNameMapper): array
    {
        $array = [];
        foreach ($data as $propertyName => $propertyValue) {
            $array = self::addToResultArray($propertyName, $propertyValue, $propertyNameMapper, $array);
        }

        return $array;
    }

    /**
     * @param string             $propertyName
     * @param mixed|object|array $propertyValue
     * @param array              $propertyNameMapper
     * @param array              $resultArray
     *
     * @return array
     */
    private static function addToResultArray(
        string $propertyName,
        $propertyValue,
        array $propertyNameMapper,
        array $resultArray
    ) {
        $mappedName = $propertyNameMapper[$propertyName]['name'] ?? $propertyNameMapper[$propertyName] ?? $propertyName;

        $resultArray[$mappedName] = self::isNestedData($propertyValue)
            ? self::toArray($propertyValue, $propertyNameMapper[$propertyName]['mapper'] ?? [])
            : $propertyValue;

        return $resultArray;
    }

    private static function isNestedData($value): bool
    {
        return is_object($value) || is_array($value);
    }
}

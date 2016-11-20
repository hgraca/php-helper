<?php
namespace Hgraca\Helper;

use Hgraca\Helper\Concept\ReflectionHelperAbstract;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

final class ClassHelper extends ReflectionHelperAbstract
{
    public static function extractCanonicalClassName(string $classFqcn): string
    {
        return substr($classFqcn, strrpos($classFqcn, '\\') + 1);
    }

    /**
     * Returns a key-value array with all constants in the class
     * It uses Reflection to find them
     *
     * @param string $classFqcn
     *
     * @return array
     */
    public static function findConstants(string $classFqcn): array
    {
        $reflectionClass = self::getReflectionClass($classFqcn);

        return $reflectionClass->getConstants();
    }

    /**
     * Returns an array with all properties names of the mixed
     *
     * @param string   $classFqcn
     * @param string[] $excludedNames
     * @param string[] $excludedVisibility = ['public', 'protected', 'private', 'static']
     *
     * @return string[]
     */
    public static function findPropertiesNames(
        string $classFqcn,
        array $excludedNames = [],
        array $excludedVisibility = []
    ) {
        $defaultVisibility  = ['public' => true, 'protected' => true, 'private' => true, 'static' => true];
        $excludedVisibility = array_merge(
            $defaultVisibility,
            self::setGivenExcludedVisivilitiesToKeysWithValueFalse($excludedVisibility)
        );

        $reflectionClass           = self::getReflectionClass($classFqcn);
        $reflectionPropertiesArray = $reflectionClass->getProperties();

        return self::filterProperties($excludedNames, $excludedVisibility, $reflectionPropertiesArray);
    }

    public static function hasMethod(string $classFqcn, string $method): bool
    {
        $reflectionClass = self::getReflectionClass($classFqcn);

        return $reflectionClass->hasMethod($method);
    }

    /**
     * @param string $classFqcn
     * @param string $method
     *
     * @return array [index => [name, class]]
     */
    public static function getParameters(string $classFqcn, string $method): array
    {
        try {
            $reflectionMethod = new ReflectionMethod($classFqcn, $method);
        }
        catch (ReflectionException $e) {
            return [];
        }

        foreach ($reflectionMethod->getParameters() as $index => $param) {
            $reflectionParameters[$index]['name'] = $param->getName();
            if (null !== $param->getClass()) {
                $reflectionParameters[$index]['class'] = $param->getClass()->name;
            }
        }

        return $reflectionParameters ?? [];
    }

    /**
     * @return ReflectionProperty[]
     */
    public static function getReflectionProperties(string $classFqcn): array
    {
        $reflectionClass = self::getReflectionClass($classFqcn);

        return $reflectionClass->getProperties();
    }

    /**
     * @param ReflectionProperty[] $propertyList
     */
    public static function setReflectionPropertiesAccessible(array &$propertyList)
    {
        foreach ($propertyList as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
        }
    }

    /**
     * Gets the property from the current class, when not available will look on parent classes before failing
     *
     * @throws ReflectionException
     *
     * @return ReflectionProperty
     */
    public static function getReflectionProperty(ReflectionClass $class, string $propertyName): ReflectionProperty
    {
        try {
            return $class->getProperty($propertyName);
        }
        catch (ReflectionException $e) {
            $parentClass = $class->getParentClass();
            if ($parentClass === false) {
                throw $e;
            }

            return self::getReflectionProperty($parentClass, $propertyName);
        }
    }

    private static function excludeProperty(
        array $excludedNames,
        array $excludedVisibility,
        ReflectionProperty $reflectionProperty
    ): bool
    {
        return in_array($reflectionProperty->getName(), $excludedNames) ||
        (! $excludedVisibility['public'] && $reflectionProperty->isPublic()) ||
        (! $excludedVisibility['protected'] && $reflectionProperty->isProtected()) ||
        (! $excludedVisibility['private'] && $reflectionProperty->isPrivate()) ||
        (! $excludedVisibility['static'] && $reflectionProperty->isStatic());
    }

    /**
     * @param string[] $excludedNames
     * @param bool[] $excludedVisibility
     * @param ReflectionProperty[] $reflectionPropertiesArray
     *
     * @return string[]
     */
    private static function filterProperties(
        array $excludedNames,
        array $excludedVisibility,
        array $reflectionPropertiesArray
    ) {
        $propertyArray = [];

        /** @var ReflectionProperty $reflectionProperty */
        foreach ($reflectionPropertiesArray as $reflectionProperty) {

            if (self::excludeProperty($excludedNames, $excludedVisibility, $reflectionProperty)) {
                continue;
            }

            $reflectionProperty->setAccessible(true);
            $propertyArray[] = $reflectionProperty->getName();
        }

        return $propertyArray;
    }

    /**
     * @param array $excludedVisibility
     *
     * @return array
     */
    private static function setGivenExcludedVisivilitiesToKeysWithValueFalse(array $excludedVisibility)
    {
        return array_fill_keys(array_keys(array_flip($excludedVisibility)), false);
    }
}

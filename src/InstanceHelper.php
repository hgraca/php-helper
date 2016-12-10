<?php

namespace Hgraca\Helper;

use Closure;
use Hgraca\Helper\Concept\ReflectionHelperAbstract;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionMethod;
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

    /**
     * @param callable $input Can be a callable array (method defaults to '__construct'), callable object or Closure
     *
     * @throws \InvalidArgumentException
     *
     * @return array [index => [name, class]]
     */
    public static function getParameters($input): array
    {
        if (is_array($input)) {
            $dependentClass  = is_string($input[0]) ? $input[0] : get_class($input[0]);
            $dependentMethod = $input[1] ?? '__construct';
            $reflectionMethod = new ReflectionMethod($dependentClass, $dependentMethod);
        } elseif ($input instanceof Closure) {
            $reflectionMethod = new ReflectionFunction($input);
        } elseif (is_callable($input)) {
            $reflectionMethod = new ReflectionMethod(get_class($input), '__invoke');
        } else {
            throw new InvalidArgumentException('$input needs to be a callable');
        }

        foreach ($reflectionMethod->getParameters() as $index => $param) {
            $reflectionParameters[$index]['name'] = $param->getName();
            if (null !== $param->getClass()) {
                $reflectionParameters[$index]['class'] = $param->getClass()->name;
            }
        }

        return $reflectionParameters ?? [];
    }
}

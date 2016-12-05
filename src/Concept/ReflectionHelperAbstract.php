<?php

namespace Hgraca\Helper\Concept;

use ReflectionClass;

abstract class ReflectionHelperAbstract extends HelperAbstract
{
    private static $reflectionClassCache = [];

    protected static function getReflectionClass(string $fqcn): ReflectionClass
    {
        return self::$reflectionClassCache[$fqcn] ?? self::$reflectionClassCache[$fqcn] = new ReflectionClass($fqcn);
    }
}

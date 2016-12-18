<?php

namespace Hgraca\Helper;

use Hgraca\Helper\Concept\HelperAbstract;

final class ArrayHelper extends HelperAbstract
{
    /**
     * Renames the given data array keys for the corresponding values in $mapper.
     * If $mapper does not have the key, the name is preserved.
     *
     * @param array $mapper [from => to]
     */
    public static function renameKeys(array $data, array $mapper): array
    {
        foreach ($mapper as $from => $to) {
            if (isset($data[$from])) {
                $data[$to] = $data[$from];
                unset($data[$from]);
            }
        }

        return $data;
    }

    /**
     * Fetch array data in dot notation path
     *
     * @return mixed|null
     */
    public static function extract(array $data, string $path)
    {
        if ('' === $path) {
            return $data;
        }

        $steps = explode('.', $path);
        $actual = $data;
        foreach ($steps as $step) {
            if (!array_key_exists($step, $actual)) {
                return null;
            }

            $actual = $actual[$step];
        }

        return $actual;
    }

    public static function isTwoDimensional(array $array): bool
    {
        $array = array_values($array);
        return isset($array[0]) && is_array($array[0]);
    }

    /**
     * Same as array_map, but recursive
     */
    public static function mapRecursive(callable $func, array $array)
    {
        return filter_var($array, \FILTER_CALLBACK, ['options' => $func]);
    }
}

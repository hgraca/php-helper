<?php

namespace Hgraca\Helper;

use Hgraca\Helper\Concept\HelperAbstract;
use InvalidArgumentException;

final class JsonHelper extends HelperAbstract
{
    public static function toArray(string $json): array
    {
        $dataArray = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON.');
        }

        return $dataArray;
    }
}

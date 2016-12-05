<?php

namespace Hgraca\Helper\Test\Stub;

class ZEntity
{
    /**
     * @var string
     */
    private $propertyZ;

    public function getPropertyZ(): string
    {
        return $this->propertyZ;
    }

    public function setPropertyZ(string $propertyZ)
    {
        $this->propertyZ = $propertyZ;
    }
}

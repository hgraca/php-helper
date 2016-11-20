<?php
namespace Hgraca\Helper\Test\Stub;

class BEntity
{
    /**
     * @var string
     */
    private $propertyA;

    /**
     * @var int
     */
    private $propertyB;

    /**
     * @var array
     */
    private $propertyC = [];

    public function getPropertyA(): string
    {
        return $this->propertyA;
    }

    public function setPropertyA(string $propertyA)
    {
        $this->propertyA = $propertyA;
    }

    public function getPropertyB(): int
    {
        return $this->propertyB;
    }

    public function setPropertyB(int $propertyB)
    {
        $this->propertyB = $propertyB;
    }

    public function getPropertyC(): array
    {
        return $this->propertyC;
    }

    public function setPropertyC(array $propertyC)
    {
        $this->propertyC = $propertyC;
    }
}

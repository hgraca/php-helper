<?php
namespace Hgraca\Helper\Test\Stub;

class CEntity extends ZEntity
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

    public function __construct(string $propertyA = 'a', int $propertyB = 1, array $propertyC = [1, 2, 3])
    {
        $this->propertyA = $propertyA;
        $this->propertyB = $propertyB;
        $this->propertyC = $propertyC;
    }

    public function getPropertyA(): string
    {
        return $this->propertyA;
    }

    public function getPropertyB(): int
    {
        return $this->propertyB;
    }

    public function getPropertyC(): array
    {
        return $this->propertyC;
    }
}

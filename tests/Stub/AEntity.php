<?php
namespace Hgraca\Helper\Test\Stub;

class AEntity
{
    const CONSTANT_A = 'CONSTANT_A';

    const CONSTANT_B = 'CONSTANT_B';

    /**
     * @var string
     */
    public $propertyA;
    /**
     * @var int
     */
    protected $propertyB;

    /**
     * @var array
     */
    private $propertyC = [];

    /**
     * @var BEntity
     */
    private static $propertyD;

    public function methodA(string $parameterA = 'A', int $parameterB = 0, BEntity $parameterC = null)
    {
    }

    protected function methodB()
    {
        $this->methodC();
    }

    private function methodC()
    {
        self::methodD();
    }

    private static function methodD()
    {
    }

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

    public static function getPropertyD(): BEntity
    {
        return self::$propertyD;
    }

    public static function setPropertyD(BEntity $propertyD)
    {
        self::$propertyD = $propertyD;
    }

}

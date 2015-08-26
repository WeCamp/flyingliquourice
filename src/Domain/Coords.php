<?php

namespace Wecamp\FlyingLiqourice\Domain;


use Assert\Assertion;

class Coords
{

    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y)
    {
        Assertion::integer($x);
        Assertion::integer($y);

        $this->x = $x;
        $this->y = $y;
    }
    /**
     * @return int
     */
    public function x()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function y()
    {
        return $this->y;
    }

    /**
     * @param Coords $coords
     * @return bool
     */
    public function equals(Coords $coords)
    {
        if($this->x !== $coords->x() || $this->y !== $coords->y()) {
            return false;
        }
        return true;
    }

}

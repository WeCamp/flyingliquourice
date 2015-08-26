<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

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
     * @param int $x
     * @param int $y
     * @return static
     */
    public static function create($x, $y)
    {
        return new static($x, $y);
    }

    /**
     * @param array $coords
     * @return static
     */
    public static function fromArray(array $coords)
    {
        return new static($coords['x'], $coords['y']);
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
        if ($this->x !== $coords->x() || $this->y !== $coords->y()) {
            return false;
        }
        return true;
    }

    /**
     * @param Coords $coords
     * @return integer
     */
    public function distance(Coords $coords)
    {
        if ($this->x == $coords->x()) {
            return abs($this->y - $coords->y());
        }

        if ($this->y == $coords->y()) {
            return abs($this->x - $coords->x());
        }

        throw new CannotCalculateDistanceBetweenDiagonalCoordsException();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%d.%d', $this->x, $this->y);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
}

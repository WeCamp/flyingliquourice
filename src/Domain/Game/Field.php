<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

use Assert\Assertion;
use Wecamp\FlyingLiqourice\Domain\Coords;

class Field
{
    /**
     * @var Coords
     */
    private $coords;

    /**
     * @var Ship
     */
    private $ship;

    /**
     * @var boolean
     */
    private $hit;

    private function __construct(Coords $coords, Ship $ship = null, $hit = false)
    {
        Assertion::boolean($hit);

        $this->coords = $coords;
        $this->ship = $ship;
        $this->hit = $hit;
    }

    /**
     * @param int $x
     * @param int $y
     * @param Ship|null $ship
     * @param bool $hit
     * @return static
     */
    public static function generate($x, $y, Ship $ship = null, $hit = false)
    {
        return new static(Coords::create($x, $y), $ship, $hit);
    }

    /**
     * @param array $field
     * @return static
     */
    public static function fromArray(array $field)
    {
        return new static(
            Coords::fromArray($field['coords']),
            Ship::fromArray($field['ship']),
            $field['hit']
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'coords' => $this->coords->toArray(),
            'ship' => $this->ship->toArray(),
            'hit' => $this->hit
        ];
    }

    /**
     * @return boolean
     */
    public function occupied()
    {
        return null !== $this->ship;
    }

    /**
     * @param Coords $coords
     * @return boolean
     */
    public function at(Coords $coords)
    {
        return $this->coords->equals($coords);
    }

    public function hit()
    {
        if ($this->hit) {
            throw new FieldAlreadyBeenHitException();
        }

        $this->hit = true;
        if ($this->occupied()) {
            $this->ship->hit();
        }
    }

    /**
     * @return bool
     */
    public function hasSunkenShip()
    {
        return ($this->occupied() && $this->ship->hasSunk());
    }

    /**
     * @return Coords
     */
    public function startPointOfShip()
    {
        if (!$this->occupied()) {
            throw new NoShipAtThisFieldException();
        }

        return $this->ship->startPoint();
    }

    /**
     * @return Coords
     */
    public function endPointOfShip()
    {
        if (!$this->occupied()) {
            throw new NoShipAtThisFieldException();
        }

        return $this->ship->endPoint();
    }
}

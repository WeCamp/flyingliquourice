<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

use Assert\Assertion;

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
    private $shot;

    private function __construct(Coords $coords, Ship $ship = null, $shot = false)
    {
        Assertion::boolean($shot);

        $this->coords = $coords;
        $this->ship = $ship;
        $this->shot = $shot;
    }

    /**
     * @param int $x
     * @param int $y
     * @param Ship|null $ship
     * @param bool $shot
     * @return static
     */
    public static function generate($x, $y, Ship $ship = null, $shot = false)
    {
        return new static(Coords::create($x, $y), $ship, $shot);
    }

    /**
     * @param array $field
     * @return static
     */
    public static function fromArray(array $field)
    {
        return new static(
            Coords::fromArray($field['coords']),
            ($field['ship'] !== null) ? Ship::fromArray($field['ship']) : null,
            $field['shot']
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'coords' => $this->coords->toArray(),
            'ship' => $this->occupied() ? $this->ship->toArray() : null,
            'shot' => $this->shot
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

    public function shoot()
    {
        if ($this->shot) {
            throw new FieldAlreadyBeenShotException();
        }

        $this->shot = true;
        if ($this->occupied()) {
            $this->ship->hit();
        }
    }

    /**
     * @return bool
     */
    public function hit()
    {
        return $this->occupied() && $this->shot;
    }

    /**
     * @return bool
     */
    public function miss()
    {
        return !$this->occupied() && $this->shot;
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

    /**
     * @return Coords
     */
    public function coords()
    {
        return $this->coords;
    }

    /**
     * @param Ship $ship
     */
    public function place(Ship $ship)
    {
        $this->ship = $ship;
    }

    public function __toString()
    {
        if ($this->hit()) {
            return '🔥';
        }

        if ($this->miss()) {
            return '💦';
        }

        if ($this->occupied() && $this->ship->startPoint()->equals($this->coords())) {
            if ($this->ship->startPoint()->x() == $this->ship->endPoint()->x()) {
                return '^';
            }

            return '<';
        }

        if ($this->occupied() && $this->ship->endPoint()->equals($this->coords())) {
            if ($this->ship->startPoint()->x() == $this->ship->endPoint()->x()) {
                return 'v';
            }

            return '>';
        }

        if ($this->occupied()) {
            if ($this->ship->startPoint()->x() == $this->ship->endPoint()->x()) {
                return 'ǁ';
            }

            return '=';
        }

        return ' ';
    }
}

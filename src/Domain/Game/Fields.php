<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

use Assert\Assertion;

class Fields implements \IteratorAggregate
{
    /**
     * @var Field[]
     */
    private $elements;

    /**
     * @var Ship[]
     */
    private $ships;

    /**
     * @param Field[] $fields
     *
     * @return static
     */
    public static function create(array $fields)
    {
        return new static($fields);
    }

    /**
     * @param Field[] $elements
     * @param Ship[] $ships
     */
    private function __construct(array $elements, array $ships = [])
    {
        Assertion::allIsInstanceOf($elements, Field::class);
        Assertion::allIsInstanceOf($ships, Ship::class);

        $this->elements = $elements;
        $this->ships = $ships;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        $ships = [];
        foreach ($data['ships'] as $ship) {
            $ships[] = Ship::fromArray($ship);
        }

        $elements = [];
        foreach ($data['fields'] as $field) {
            $field = Field::fromArray($field);

            /** @var Ship $ship */
            foreach ($ships as $ship) {
                if ($ship->on($field->coords())) {
                    $field->place($ship);
                }
            }

            $elements[] = $field;
        }

        return new static($elements, $ships);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $fields = [];
        foreach ($this->elements as $element) {
            $fields[] = $element->toArray();
        }

        $ships = [];
        foreach ($this->ships as $ship) {
            $ships[] = $ship->toArray();
        }

        return ['fields' => $fields, 'ships' => $ships];
    }

    /**
     * @param Coords $coords
     */
    public function shoot(Coords $coords)
    {
        foreach ($this->elements as $element) {
            if ($element->at($coords)) {
                $element->hit();
                $element->shoot();

                return;
            }
        }
    }

    /**
     * @param Coords $coords
     *
     * @return bool
     */
    public function didShipSankAt(Coords $coords)
    {
        foreach ($this->elements as $element) {
            if ($element->at($coords)) {
                return $element->hasSunkenShip();
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function didAllShipsSink()
    {
        foreach ($this->elements as $element) {
            if ($element->occupied() && !$element->hasSunkenShip()) {
                return false;
            }
        }

        return true;
    }


    /**
     * @param Coords $coords
     * @return Coords
     */
    public function startPointOfShipAt(Coords $coords)
    {
        foreach ($this->elements as $element) {
            if ($element->at($coords)) {
                return $element->startPointOfShip();
            }
        }
        throw new NoShipAtTheseCoordsException();
    }


    /**
     * @param Coords $coords
     * @return Coords
     */
    public function endPointOfShipAt(Coords $coords)
    {
        foreach ($this->elements as $element) {
            if ($element->at($coords)) {
                return $element->endPointOfShip();
            }
        }
        throw new NoShipAtTheseCoordsException();
    }

    /**
     * @param Coords $spot
     *
     * @return Field
     */
    public function at(Coords $spot)
    {
        foreach ($this->elements as $field) {
            if ($field->coords()->equals($spot)) {
                return $field;
            }
        }
    }

    /**
     * @param Coords $spot
     *
     * @return bool
     */
    public function hasAt(Coords $spot)
    {
        foreach ($this->elements as $field) {
            if ($field->coords()->equals($spot)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Ship $ship
     */
    public function place(Ship $ship)
    {
        foreach ($this->elements as $field) {
            if ($ship->on($field->coords())) {
                $field->place($ship);
            }
        }

        $this->ships[] = $ship;
    }

    public function __toString()
    {
        $result     = PHP_EOL . '|';
        $currentRow = 0;
        foreach ($this->elements as $element) {
            if ($element->coords()->y() > $currentRow) {
                $result .= '|' . PHP_EOL . '|';
                $currentRow++;
            }

            $result .= (string) $element;
        }

        return $result . '|' . PHP_EOL;
    }

    /**
     * @return Ship[]
     */
    public function ships()
    {
        return $this->ships;
    }
}

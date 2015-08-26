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
     * @param Field[] $elements
     */
    private function __construct(array $elements)
    {
        Assertion::allIsInstanceOf($elements, Field::class);

        $this->elements = $elements;
    }

    /**
     * @return array
     */
    private static function generateShips()
    {
        $ships = [
            [
                Ship::create(Coords::create(4, 9), Coords::create(9, 9)),
                Ship::create(Coords::create(6, 0), Coords::create(9, 0)),
                Ship::create(Coords::create(0, 2), Coords::create(0, 5)),
                Ship::create(Coords::create(3, 0), Coords::create(3, 2)),
                Ship::create(Coords::create(5, 2), Coords::create(7, 2)),
                Ship::create(Coords::create(9, 5), Coords::create(9, 7)),
                Ship::create(Coords::create(0, 0), Coords::create(1, 0)),
                Ship::create(Coords::create(9, 2), Coords::create(9, 3)),
                Ship::create(Coords::create(5, 5), Coords::create(5, 6)),
                Ship::create(Coords::create(0, 7), Coords::create(1, 7))
            ],
            [
                Ship::create(Coords::create(0, 0), Coords::create(0, 5)),
                Ship::create(Coords::create(1, 0), Coords::create(1, 3)),
                Ship::create(Coords::create(2, 0), Coords::create(2, 3)),
                Ship::create(Coords::create(3, 0), Coords::create(3, 2)),
                Ship::create(Coords::create(4, 0), Coords::create(4, 2)),
                Ship::create(Coords::create(5, 0), Coords::create(5, 2)),
                Ship::create(Coords::create(6, 0), Coords::create(6, 1)),
                Ship::create(Coords::create(7, 0), Coords::create(7, 1)),
                Ship::create(Coords::create(8, 0), Coords::create(8, 1)),
                Ship::create(Coords::create(9, 0), Coords::create(9, 1))
            ],
            [
                Ship::create(Coords::create(9, 4), Coords::create(9, 9)),
                Ship::create(Coords::create(0, 6), Coords::create(0, 9)),
                Ship::create(Coords::create(2, 0), Coords::create(5, 0)),
                Ship::create(Coords::create(0, 3), Coords::create(2, 3)),
                Ship::create(Coords::create(2, 5), Coords::create(2, 7)),
                Ship::create(Coords::create(5, 9), Coords::create(7, 9)),
                Ship::create(Coords::create(0, 0), Coords::create(0, 1)),
                Ship::create(Coords::create(2, 9), Coords::create(3, 9)),
                Ship::create(Coords::create(5, 5), Coords::create(6, 5)),
                Ship::create(Coords::create(7, 0), Coords::create(7, 1))
            ],
        ];

        return $ships[array_rand($ships)];
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @param int $width
     * @param int $height
     * @param array $shipSizes
     * @return static
     */
    public static function generate($width, $height, array $shipSizes)
    {
        Assertion::integer($width);
        Assertion::integer($height);
        Assertion::allInteger($shipSizes);

        $ships = self::generateShips();

        $elements = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $shipOnField = null;
                foreach ($ships as $ship) {
                    /** @var Ship $ship */
                    if ($ship->on(Coords::create($x, $y))) {
                        $shipOnField = $ship;
                        break;
                    }
                }

                $elements[] = Field::generate(
                    $x,
                    $y,
                    $shipOnField
                );
            }
        }
        return new static ($elements);
    }

    /**
     * @param array $fields
     * @return static
     */
    public static function fromArray(array $fields)
    {
        $elements = [];
        foreach ($fields as $field) {
            $elements[] = Field::fromArray($field);
        }
        return new static ($elements);
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
        return $fields;
    }

    /**
     * @param Coords $coords
     */
    public function shoot(Coords $coords)
    {
        foreach($this->elements as $element) {
            if ($element->at($coords)) {
                $element->shoot();
                return;
            }
        }
    }

    /**
     * @param Coords $coords
     * @return bool
     */
    public function didShipSankAt(Coords $coords)
    {
        foreach($this->elements as $element) {
            if ($element->at($coords)) {
                return $element->hasSunkenShip();
            }
        }
        return false;
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
    }

    public function __toString()
    {
        $result = '';
        $currentRow = 0;
        foreach ($this->elements as $element) {
            if ($element->coords()->y() > $currentRow) {
                $result .= PHP_EOL;
                $currentRow++;
            }

            $result .= $this->determineElementOutput($element);
        }

        return $result;
    }

    private function determineElementOutput(Field $element)
    {
        if ($element->hit()) {
            return 'X';
        }

        if ($element->miss()) {
            return 'O';
        }

        if ($element->occupied()) {
            return '·';
        }

        return ' ';
    }
}

<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

use Assert\Assertion;
use Wecamp\FlyingLiqourice\Domain\Coords;

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
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * @return static
     */
    public static function generate($width, $height, array $shipSizes)
    {
        Assertion::integer($width);
        Assertion::integer($height);
        Assertion::allInteger($shipSizes);

        // @Todo Something spiffy to place ships
        $elements = [];
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $elements[] = Field::generate(
                    $x,
                    $y,
                    Ship::create(
                        Coords::create($x, $y),
                        Coords::create($x, $y)
                    )
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
    public function hit(Coords $coords)
    {
        foreach($this->elements as $element) {
            if ($element->at($coords)) {
                $element->hit();
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
}

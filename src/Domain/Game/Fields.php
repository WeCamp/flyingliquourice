<?php


namespace Wecamp\FlyingLiqourice\Domain\Game;


use Assert\Assertion;

class Fields implements \IteratorAggregate
{
    /*
     * @var Field[]
     */
    private $elements;

    private function __construct(array $elements)
    {
        Assertion::allIsInstanceOf($elements, Field::class);

        $this->elements = $elements;
    }

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

        $ships = [];
        foreach ($shipSizes as $shipSize) {
            $ships[] = Ship::create($shipSize);
        }

        //@Todo Something spiffy to place ships
        $elements = [];
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $elements[] = Field::generate($x, $y, Ship::create(1));
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
            /** @var Field $element */
            if ($element->at($coords)) {
                return $element->hasSunkenShip();
            }
        }
        return false;
    }


}

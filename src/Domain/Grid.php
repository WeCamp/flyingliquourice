<?php

namespace Wecamp\FlyingLiqourice\Domain;

use Assert\Assertion;
use Wecamp\FlyingLiqourice\Domain\Game\CoordsNotInGridException;
use Wecamp\FlyingLiqourice\Domain\Game\Field;
use Wecamp\FlyingLiqourice\Domain\Game\Fields;

class Grid
{
    const DEFAULT_WIDTH = 10;
    const DEFAULT_HEIGHT = 10;

    /**
     * @var array
     */
    private static $defaultShipSizes = [6,4,4,3,3,3,2,2,2,2];

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var Fields|Field[]
     */
    private $fields;

    public function __construct($width, $height, Fields $fields)
    {
        Assertion::integer($width);
        Assertion::integer($height);

        $this->width = $width;
        $this->height = $height;
        $this->fields = $fields;
    }

    /**
     * @param array $grid
     * @return static
     */
    public static function fromArray(array $grid)
    {
        return new static($grid['width'], $grid['height'], Fields::fromArray($grid['fields']));
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new static(
            static::DEFAULT_WIDTH,
            static::DEFAULT_HEIGHT,
            Fields::generate(static::DEFAULT_WIDTH, static::DEFAULT_HEIGHT, static::$defaultShipSizes)
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['width' => $this->width, 'height' => $this->height, 'fields'=> $this->fields->toArray()];
    }

    /**
     * Checks if coordinates are inside the field
     *
     * @param Coords $coords
     * @return bool
     */
    public function has(Coords $coords)
    {
        $x = $coords->x();

        if ($x < 0 || $x > $this->width) {
            return false;
        }

        $y = $coords->y();

        if ($y < 0 || $y > $this->height) {
            return false;
        }

        return true;
    }

    /**
     * @param Coords $coords
     * @return bool
     */
    public function hasShipAt(Coords $coords)
    {
        $this->ensureCoordsInGrid($coords);

        foreach($this->fields as $field) {
            if ($field->at($coords) && $field->occupied()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Coords $coords
     */
    public function hitAt(Coords $coords)
    {
        $this->ensureCoordsInGrid($coords);

        $this->fields->hit($coords);
    }

    /**
     * @param Coords $coords
     * @return bool
     */
    public function didShipSankAt(Coords $coords)
    {
        $this->ensureCoordsInGrid($coords);

        return $this->fields->didShipSankAt($coords);
    }

    /**
     * @param Coords $coords
     * @return Coords
     */
    public function startPointOfShipAt(Coords $coords)
    {
        $this->ensureCoordsInGrid($coords);

        return $this->fields->startPointOfShipAt($coords);
    }

    /**
     * @param Coords $coords
     * @return Coords
     */
    public function endPointOfShipAt(Coords $coords)
    {
        $this->ensureCoordsInGrid($coords);

        return $this->fields->endPointOfShipAt($coords);
    }

    /**
     * @param Coords $coords
     */
    private function ensureCoordsInGrid(Coords $coords)
    {
        if (!$this->has($coords)) {
            throw new CoordsNotInGridException();
        }
    }
}

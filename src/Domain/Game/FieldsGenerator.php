<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

use Assert\Assertion;

final class FieldsGenerator
{
    /**
     * @param int $width
     * @param int $height
     * @param array $shipSizes
     * @return Fields
     */
    public static function generate($width, $height, array $shipSizes)
    {
        Assertion::integer($width);
        Assertion::integer($height);
        Assertion::allInteger($shipSizes);

        $elements = [];
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $elements[] = Field::generate($x, $y);
            }
        }
        $fields = Fields::create($elements);

        foreach ($shipSizes as $shipSize) {
            // Pick a random coordinate
            while (true) {
                $direction = (mt_rand(0, 1) == 0) ? 'right' : 'below';
                $spot = Coords::create(
                    mt_rand(0, $width - 1),
                    mt_rand(0, $height - 1)
                );

                $endPoint = static::validEndpoint($fields, $spot, $shipSize, $direction);
                if ($endPoint === null) {
                    continue;
                }

                // If we end up here the ship can fit at the determined spot
                $fields->place(
                    Ship::create($spot, $endPoint)
                );
                break;
            }
        }

        return $fields;
    }

    private static function validEndpoint(Fields $fields, Coords $spot, $shipSize, $direction)
    {
        /** @var Field $field */
        $field = $fields->at($spot);
        if ($field->occupied()) {
            return;
        }

        if (!$fields->hasAt($spot->$direction($shipSize))) {
            return;
        }

        $neighbour = null;
        for ($i = 1; $i <= $shipSize; $i++) {
            $neighbour = $fields->at($spot->$direction($i));
            if ($neighbour->occupied()) {
                return;
            }
        }

        return $neighbour->coords();
    }
}

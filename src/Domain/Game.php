<?php

namespace Wecamp\FlyingLiqourice\Domain;

use Wecamp\FlyingLiqourice\Domain\Game\FireResult;

final class Game
{
    /**
     * @var Identifier
     */
    private $id;

    /**
     * @var Grid
     */
    private $grid;

    /**
     * Creates a new game.
     *
     * @return static
     */
    public static function create()
    {
        return new static(
            GameIdentifier::generate(),
            Grid::generate()
        );
    }

    /**
     * @param Coords $coords
     * @return static
     */
    public function fire(Coords $coords)
    {
        $this->grid->hitAt($coords);

        if (!$this->grid->hasShipAt($coords)) {
            return FireResult::miss();
        }


        //@todo Check for win

//        if ($this->grid->didAllShipsSank()) {
//            return FireResult::win();
//        }

        if ($this->grid->didShipSankAt($coords)) {
            return FireResult::sank(
                $this->grid->startPointOfShipAt($coords),
                $this->grid->endPointOfShipAt($coords)
            );
        }

        return FireResult::hit();
    }

    /**
     * Recreates a game from an array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static(
            GameIdentifier::fromString($data['id']),
            Grid::fromArray($data['grid'])
        );
    }

    /**
     * Get the identifier of this game.
     *
     * @return Identifier
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Converts this game to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => (string) $this->id,
            'grid' => $this->grid->toArray()
        ];
    }

    /**
     * @param Identifier $id
     * @param Grid $grid
     */
    private function __construct(Identifier $id, Grid $grid)
    {
        $this->id = $id;
        $this->grid = $grid;
    }
}

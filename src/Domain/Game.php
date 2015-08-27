<?php

namespace Wecamp\FlyingLiqourice\Domain;

use Assert\Assertion;
use Wecamp\FlyingLiqourice\Domain\Game\Coords;
use Wecamp\FlyingLiqourice\Domain\Game\FireResult;
use Wecamp\FlyingLiqourice\Domain\Game\GameIsLockedException;
use Wecamp\FlyingLiqourice\Domain\Game\Grid;

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
     * @var bool
     */
    private $locked;

    /**
     * Creates a new game.
     *
     * @param int $width
     * @param int $height
     * @param array $shipSizes
     *
     * @return static
     */
    public static function create($width = null, $height = null, array $shipSizes = null)
    {
        return new static(
            GameIdentifier::generate(),
            Grid::generate($width, $height, $shipSizes)
        );
    }

    /**
     * @param Coords $coords
     * @return FireResult
     */
    public function fire(Coords $coords)
    {
        if ($this->locked()) {
            throw new GameIsLockedException;
        }

        $this->grid->shoot($coords);

        if (!$this->grid->hasShipAt($coords)) {
            return FireResult::miss();
        }

        if ($this->grid->didAllShipsSink()) {
            $this->lock();

            return FireResult::win(
                $this->grid->startPointOfShipAt($coords),
                $this->grid->endPointOfShipAt($coords)
            );
        }

        if ($this->grid->didShipSankAt($coords)) {
            return FireResult::sank(
                $this->grid->startPointOfShipAt($coords),
                $this->grid->endPointOfShipAt($coords)
            );
        }

        return FireResult::hit();
    }

    /**
     * @return Game\Ship[]
     */
    public function surrender()
    {
        $this->lock();

        return $this->grid->ships();
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
            Grid::fromArray($data['grid']),
            $data['locked']
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
            'grid' => $this->grid->toArray(),
            'locked' => $this->locked
        ];
    }

    public function __toString()
    {
        return (string) $this->id() . PHP_EOL . ((string) $this->grid) . PHP_EOL;
    }

    /**
     * @param Identifier $id
     * @param Grid $grid
     * @param bool $locked
     */
    private function __construct(Identifier $id, Grid $grid, $locked = false)
    {
        Assertion::boolean($locked);

        $this->id = $id;
        $this->grid = $grid;
        $this->locked = $locked;
    }

    private function lock()
    {
        $this->locked = true;
    }

    /**
     * @return bool
     */
    private function locked()
    {
        return $this->locked;
    }
}

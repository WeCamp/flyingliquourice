<?php

namespace Wecamp\FlyingLiqourice\Domain;

final class Game
{
    /**
     * @var Identifier
     */
    private $id;

    /**
     * Creates a new game.
     *
     * @return static
     */
    public static function create()
    {
        return new static(
            GameIdentifier::generate()
        );
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
            GameIdentifier::fromString($data['id'])
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
            'id' => (string) $this->id
        ];
    }

    /**
     * @param Identifier $id
     */
    private function __construct(Identifier $id)
    {
        $this->id = $id;
    }
}

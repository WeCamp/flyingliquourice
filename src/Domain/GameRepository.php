<?php

namespace Wecamp\FlyingLiqourice\Domain;

interface GameRepository
{
    /**
     * @param Game $game
     */
    public function save(Game $game);

    /**
     * @param Identifier $identifier
     * @return Game
     */
    public function get(Identifier $identifier);
}

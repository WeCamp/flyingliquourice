<?php

namespace Wecamp\FlyingLiquorice\Domain;

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

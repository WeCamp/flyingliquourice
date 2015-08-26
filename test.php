<?php

require __DIR__ . '/vendor/autoload.php';

$game = Wecamp\FlyingLiqourice\Domain\Game::create();

for ($i = 0; $i < 10; $i++) {
    try {
        $game->fire(\Wecamp\FlyingLiqourice\Domain\Game\Coords::create(mt_rand(0, 9), mt_rand(0, 9)));
    } catch (Wecamp\FlyingLiqourice\Domain\Game\FieldAlreadyBeenShotException $e) {
        // hihi
    }
}

echo $game;

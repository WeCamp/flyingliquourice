<?php

require __DIR__ . '/vendor/autoload.php';

$game = Wecamp\FlyingLiqourice\Domain\Game::create();

$game->fire(\Wecamp\FlyingLiqourice\Domain\Coords::create(0, 0));
$game->fire(\Wecamp\FlyingLiqourice\Domain\Coords::create(3, 3));
$game->fire(\Wecamp\FlyingLiqourice\Domain\Coords::create(5, 0));
$game->fire(\Wecamp\FlyingLiqourice\Domain\Coords::create(9, 9));

echo $game;

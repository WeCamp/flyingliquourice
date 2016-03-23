<?php

require __DIR__ . '/vendor/autoload.php';

$game = Wecamp\FlyingLiquorice\Domain\Game::create(10, 10);
echo $game;

while (true) {
    try {
        $game->fire(\Wecamp\FlyingLiquorice\Domain\Game\Coords::create(mt_rand(0, 9), mt_rand(0, 9)));
    } catch (Wecamp\FlyingLiquorice\Domain\Game\FieldAlreadyBeenShotException $e) {
        // Ignore
    } catch (Wecamp\FlyingLiquorice\Domain\Game\GameIsLockedException $e) {
        // Won the game
        break;
    }
}

echo $game;

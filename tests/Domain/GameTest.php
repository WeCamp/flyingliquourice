<?php

namespace Wecamp\FlyingLiqourice\Domain;

use Wecamp\FlyingLiqourice\Domain\Game\Coords;
use Wecamp\FlyingLiqourice\Domain\Game\FireResult;
use Wecamp\FlyingLiqourice\Domain\Game\GameIsLockedException;

final class GameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_return_the_status()
    {
        $game   = Game::create();
        $coords = Coords::fromArray(['x' => 1, 'y' => 1]);

        $fireResult = $game->fire($coords);
        $status     = $game->status();

        $this->assertCount(1, $status);
        $this->assertInstanceOf(FireResult::class, $status[0]);
        $this->assertSame($fireResult, $status[0]);
    }

    /**
     * @test
     */
    public function it_should_return_the_fire_results_in_the_correct_order()
    {
        $game = Game::create();

        $fireResults = [
            $game->fire(Coords::fromArray(['x' => 1, 'y' => 1])),
            $game->fire(Coords::fromArray(['x' => 5, 'y' => 5])),
            $game->fire(Coords::fromArray(['x' => 2, 'y' => 2])),
            $game->fire(Coords::fromArray(['x' => 4, 'y' => 4])),
            $game->fire(Coords::fromArray(['x' => 3, 'y' => 3])),
        ];

        $status = $game->status();

        $this->assertSame($fireResults, $status);
    }

    /**
     * @test
     */
    public function it_should_return_the_grid_as_a_string()
    {
        $game = Game::create(10, 10);

        $this->assertSame(269, mb_strlen((string) $game));
    }

    /**
     * @test
     */
    public function it_should_not_allow_to_fire_in_a_locked_game()
    {
        $game = Game::create();
        $game->surrender();

        $this->setExpectedException(GameIsLockedException::class);
        $game->fire(Coords::create(1, 1));
    }

    /**
     * @test
     */
    public function it_should_win_the_game_when_all_ships_have_sunk()
    {
        $game = Game::create(1, 2, [2]);

        $result = $game->fire(Coords::fromArray(['x' => 0, 'y' => 0]));
        $this->assertFalse($result->isWon());

        $result  = $game->fire(Coords::fromArray(['x' => 0, 'y' => 1]));
        $this->assertTrue($result->isWon());
    }
}

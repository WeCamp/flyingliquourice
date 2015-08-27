<?php

namespace Wecamp\FlyingLiqourice\Domain;

use Wecamp\FlyingLiqourice\Domain\Game\Coords;
use Wecamp\FlyingLiqourice\Domain\Game\FireResult;

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
}

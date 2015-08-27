<?php

namespace Wecamp\FlyingLiqourice\Domain;

use Rhumsaa\Uuid\Uuid;
use Wecamp\FlyingLiqourice\Domain\Game\Coords;
use Wecamp\FlyingLiqourice\Domain\Game\Grid;

final class GameTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_sinks_a_ship()
    {
        $gridData = [
            'fields' => [
                [
                    'coords' => [
                        'x'=>0,
                        'y'=>0
                    ],
                    'ship' => [
                        'startPoint' => [
                            'x'=>0,
                            'y'=>0
                        ],
                        'endPoint' => [
                            'x'=>0,
                            'y'=>1
                        ],
                        'hits' => 0
                    ],
                    'shot' => false
                ],
                [
                    'coords' => [
                        'x'=>0,
                        'y'=>1
                    ],
                    'ship' => [
                        'startPoint' => [
                            'x'=>0,
                            'y'=>0
                        ],
                        'endPoint' => [
                            'x'=>0,
                            'y'=>1
                        ],
                        'hits' => 0
                    ],
                    'shot' => false
                ]
            ],
            'ships' => [
                [
                    'startPoint' => [
                        'x'=>0,
                        'y'=>0
                    ],
                    'endPoint' => [
                        'x'=>0,
                        'y'=>1
                    ],
                    'hits' => 0
                ]
            ]
        ];

        $data = [
            'width' => 10,
            'height' => 10,
            'fields' => $gridData
        ];
        $gameData = ['id' => Uuid::uuid4()->toString(), 'locked' => false, 'grid' => $data];
        $game = Game::fromArray($gameData);
        $result = $game->fire(Coords::create(0, 0));

        $this->assertEquals('HIT', (string) $result);

        $result = $game->fire(Coords::create(0, 1));

        $this->assertEquals('SINK', (string) $result);
    }
}

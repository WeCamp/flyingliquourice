<?php

namespace Wecamp\FlyingLiqourice\Service;

use Wecamp\FlyingLiqourice\Domain\Game\Coords;
use Wecamp\FlyingLiqourice\Domain\Game;
use Wecamp\FlyingLiqourice\Domain\GameIdentifier;
use Wecamp\FlyingLiqourice\Storage\SqliteGameRepository;

class ServiceListener
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $id;

    /**
     * @var SqliteGameRepository
     */
    private $repository;

    public function __construct($token, $id = '', $repository)
    {
        $this->token = strtolower($token);

        $this->id = $id;

        $this->repository = $repository;
    }

    /**
     * Run the command in the token
     * @return string
     */
    public function run()
    {
        if (!empty($this->id)) {
            $identifier = GameIdentifier::fromString($this->id());
            $game       = $this->repository()->get($identifier);

            echo 'Re initializing game id: ' . $game->id() . PHP_EOL;
        }
        $tokenized = explode(' ', $this->token);

        $command  = trim($tokenized[0]);
        $argument = '';
        if (count($tokenized) == 2) {
            $argument = trim($tokenized[1]);
        }

        if (!in_array($command, ['start', 'f', 'fire', 'status', 'surrender', 'field'])) {
            throw new \InvalidArgumentException('Wrong command given');
        }

        return $this->$command($argument);
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return SqliteGameRepository
     */
    private function repository()
    {
        return $this->repository;
    }

    /**
     * @param string $id
     * @return string
     */
    private function start($id = '')
    {
        if (strlen($id) !== 0) {
            $identifier = GameIdentifier::fromString($id);
            $game       = $this->repository()->get($identifier);
            echo 'Game restarted: ' . $game->id() . PHP_EOL;
        } else {
            $game = Game::create();
            echo 'New game started: ' . $game->id() . PHP_EOL;
        }

        $this->id = $game->id();
        $this->repository()->save($game);

        echo (string) $game;
        return 'STARTED ' . $game->id();
    }

    /**
     * Get the status of the current game
     * @return string
     */
    private function status()
    {
        $identifier = GameIdentifier::fromString($this->id());
        $game       = $this->repository()->get($identifier);

        echo 'Get game status: ' . $game->id() . PHP_EOL;
        echo (string) $game;

        $result = '';
        $status = $game->status();
        foreach ($status as $fireResult) {
            $result .= '- ' . $fireResult . PHP_EOL;
        }

        return 'STATUS' . PHP_EOL . $result;
    }

    /**
     * Quit the game
     * @return string
     */
    private function surrender()
    {
        $identifier = GameIdentifier::fromString($this->id());
        $game       = $this->repository()->get($identifier);

        $game->surrender();
        $this->repository()->save($game);

        echo 'Surrendering game ' . $game->id() . PHP_EOL;
        echo (string) $game;

        $result = 'SURRENDERED';
        foreach ($game->ships() as $ship) {
            $result .= '- SHIP ' . $ship->startPoint() . ' ' . $ship->endPoint() . PHP_EOL;
        }
        return $result;
    }

    /**
     * Shortcut for fire
     * @param $location
     * @return string
     */
    private function f($location)
    {
        return $this->fire($location);
    }

    /**
     * fire on $location
     * @param string $location
     * @return string
     */
    private function fire($location)
    {
        $identifier = GameIdentifier::fromString($this->id());
        $game       = $this->repository()->get($identifier);

        $coordElements = explode('.', $location);
        $coords        = Coords::create((int) $coordElements[0], (int) $coordElements[1]);
        $result        = $game->fire($coords);

        $this->repository()->save($game);

        echo 'Firing on ' . $location . ' in game: ' . $game->id() . PHP_EOL;
        echo (string) $game;
        return $result;
    }

    private function field()
    {
        $identifier = GameIdentifier::fromString($this->id());
        $game       = $this->repository()->get($identifier);

        $this->repository()->save($game);
        return 'FIELD ' . PHP_EOL . $game;
    }
}

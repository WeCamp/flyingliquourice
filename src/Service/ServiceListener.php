<?php

namespace Wecamp\FlyingLiqourice\Service;

use Wecamp\FlyingLiqourice\Domain\Game;

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

    public function __construct($token, $id = '')
    {
        $this->token = strtolower($token);

        $this->id = $id;

    }

    public function run()
    {
        if (!empty($this->id)) {
            $game = Game::fromArray(['id' => $this->id]);
            echo 'Re initializing game id: ' . $game->id() . PHP_EOL;
        }
        $tokenized = explode(' ', $this->token);
        $result = '';

        $command = trim($tokenized[0]);
        $argument = '';
        if (count($tokenized) == 2) {
            $argument = trim($tokenized[1]);
        }
        if (method_exists($this, $command)
            && $command !== 'run'
        ) {
            return $this->$command($argument);
        }

        throw new \InvalidArgumentException('Wrong command given');
    }

    public function id()
    {
        return $this->id;
    }

    protected function start($id = '')
    {
        if (strlen($id) !== 0) {
            $game = Game::fromArray(['id' => $id]);
            echo 'Game restarted: ' . $game->id() . PHP_EOL;
        } else {
            $game = Game::create();
            echo 'New game started: ' . $game->id() . PHP_EOL;
        }

        $this->id = $game->id();
        $result = json_encode($game->toArray());

        return $result;
    }

    protected function status()
    {
        $game = Game::fromArray(['id' => $this->id()]);
        $result = json_encode($game->toArray());
        echo 'Get game status: ' . $game->id() . PHP_EOL;
        return $result;
    }

    protected function quit($id = '')
    {
        $game = Game::fromArray(['id' => $this->id()]);

        $this->id = $game->id();
        $result = json_encode('You lost');
        echo 'Quiting game' . $game->id() . PHP_EOL;
        return $result;
    }

    protected function fire($location)
    {
        $game = Game::fromArray(['id' => $this->id()]);
        $coords = explode('.', $location);

        $result = json_encode(sprintf('Shot has been fired on %d-%d', $coords[0], $coords[1]));
        echo 'Firing on ' . $location . ' in game: ' . $game->id() . PHP_EOL;
        return $result;
    }
}

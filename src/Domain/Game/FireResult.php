<?php


namespace Wecamp\FlyingLiqourice\Domain\Game;


use Assert\Assertion;

class FireResult
{
    /*
     * @var string
     */
    private $result;

    CONST MISS = 'MISS';
    CONST HIT = 'HIT';

    private function __construct($result)
    {
        Assertion::choice($result, [static::HIT, static::MISS]);
        $this->result = $result;
    }

    /**
     * @return static
     */
    public static function miss()
    {
        return new static(static::MISS);
    }

    /**
     * @return static
     */
    public static function hit()
    {
        return new static(static::HIT);
    }

    public function __toString()
    {
        return $this->result;
    }
}

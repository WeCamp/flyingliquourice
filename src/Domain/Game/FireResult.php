<?php


namespace Wecamp\FlyingLiqourice\Domain\Game;


use Assert\Assertion;

class FireResult
{
    const MISS = 'MISS';
    const HIT = 'HIT';

    /**
     * @var string
     */
    private $result;

    /**
     * @param string $result
     */
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->result;
    }
}

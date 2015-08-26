<?php

namespace Wecamp\FlyingLiqourice\Domain\Game;

use Assert\Assertion;

class FireResult
{
    const MISS = 'MISS';
    const HIT = 'HIT';
    const SANK = 'SANK';

    /**
     * @var string
     */
    private $result;

    /**
     * @var Coords|null
     */
    private $startPoint;

    /**
     * @var Coords|null
     */
    private $endPoint;

    /**
     * @param string $result
     * @param Coords $startPoint
     * @param Coords $endPoint
     */
    private function __construct($result, Coords $startPoint = null, Coords $endPoint = null)
    {
        Assertion::choice($result, [static::HIT, static::MISS, static::SANK]);

        if ($result == static::SANK) {
            Assertion::notNull($startPoint);
            Assertion::notNull($endPoint);
        }

        $this->result = $result;
        $this->startPoint = $startPoint;
        $this->endPoint = $endPoint;
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
     * @param Coords $startPoint
     * @param Coords $endPoint
     * @return static
     */
    public static function sank(Coords $startPoint, Coords $endPoint)
    {
        return new static(static::SANK, $startPoint, $endPoint);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        if ($this->result == static::SANK) {
            return $this->result . ' ' . ((string) $this->startPoint) . ' ' . ((string) $this->endPoint);
        }

        return $this->result;
    }
}

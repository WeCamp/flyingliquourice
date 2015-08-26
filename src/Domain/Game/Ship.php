<?php


namespace Wecamp\FlyingLiqourice\Domain\Game;


use Assert\Assertion;

class Ship
{
    /**
     * @var int
     */
    private $size;

    /**
     * @var int
     */
    private $hits = 0;

    private function __construct($size, $hits = 0)
    {
        Assertion::integer($size);
        Assertion::integer($hits);

        Assertion::greaterThan($size, 0);
        Assertion::greaterOrEqualThan($hits, 0);
        Assertion::lessOrEqualThan($hits, $size);

        $this->size = $size;
        $this->hits = $hits;
    }

    /**
     * @param array $ship
     * @return static
     */
    public static function fromArray(array $ship)
    {
        return new static ($ship['size'], $ship['hits']);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'size'=>$this->size,
            'hits'=>$this->hits
        ];
    }

    /**
     * @param integer $size
     * @return static
     */
    public static function create($size)
    {
        return new static ($size);
    }

    public function hit()
    {
        $this->hits++;
    }

    public function hasSunk()
    {
        return ($this->hits === $this->size);
    }

}

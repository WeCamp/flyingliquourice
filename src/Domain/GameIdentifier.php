<?php

namespace Wecamp\FlyingLiquorice\Domain;

use Assert\Assertion;
use Assert\InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class GameIdentifier implements Identifier
{
    /**
     * @var string UUID v4 representing a globally unique identifier
     */
    private $identifier;

    /**
     * Initializes this identifier with a UUID.
     *
     * @param string $identifier
     *
     * @throws InvalidArgumentException if the provided value is not a valid UUID.
     */
    private function __construct($identifier)
    {
        Assertion::uuid($identifier);

        $this->identifier = $identifier;
    }

    /**
     * Generates a new identifier with a freshly generated UUID.
     *
     * @return static
     */
    public static function generate()
    {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * @param Identifier $identifier
     * @return bool
     */
    public function equals(Identifier $identifier)
    {
        return get_class($this) == get_class($identifier)
            && $this->identifier == (string) $identifier;
    }

    /**
     * Returns a new instance of this identifier with the given UUID.
     *
     * @param string $uuid
     *
     * @return static
     */
    public static function fromString($uuid)
    {
        return new static($uuid);
    }

    /**
     * Returns this identifier as a string, meaning that the UUID contained in this identifier is returned.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->identifier;
    }
}

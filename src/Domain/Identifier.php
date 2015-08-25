<?php

namespace Wecamp\FlyingLiqourice\Domain;

/**
 * Represents the minimal methods that an identifier should provide.
 */
interface Identifier
{
    /**
     * Generates a new identifier with a freshly generated UUID.
     *
     * @return static
     */
    public static function generate();

    /**
     * Returns a new instance of this identifier with the given UUID.
     *
     * @param string $uuid
     *
     * @return static
     */
    public static function fromString($uuid);

    /**
     * Returns whether this identifier is the same as a given identifier.
     *
     * @param Identifier $identifier
     * @return bool
     */
    public function equals(Identifier $identifier);

    /**
     * Returns this identifier as a string, meaning that the UUID contained in this identifier is returned.
     *
     * @return string
     */
    public function __toString();
}

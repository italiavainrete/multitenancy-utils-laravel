<?php

namespace IVR\MultiTenancyUtils\Exceptions;

use RuntimeException;

/**
 * Thrown when the identity provider (user area) does not accept the OAuth token
 * stored on the local user: 401/403, empty or malformed profile response, or
 * no token at all. The local session must be considered dead.
 */
class InvalidUserTokenException extends RuntimeException
{
    public static function emptyToken(): self
    {
        return new self('The local user has no OAuth token.');
    }

    public static function rejected(int $status): self
    {
        return new self("The identity provider rejected the OAuth token (HTTP {$status}).", $status);
    }

    public static function invalidResponse(int $status): self
    {
        return new self("The identity provider returned an empty or invalid profile response (HTTP {$status}).", $status);
    }
}

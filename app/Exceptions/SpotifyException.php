<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class SpotifyException extends Exception
{
    public static function authenticationFailed(string $message): self
    {
        return new self("Spotify authentication failed: {$message}");
    }

    public static function apiError(string $message, int $code = 0): self
    {
        return new self("Spotify API error: {$message}", $code);
    }

    public static function invalidState(): self
    {
        return new self("Invalid state parameter. Possible CSRF attack.");
    }
}

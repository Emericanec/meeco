<?php

declare(strict_types=1);


namespace App\Exception\Common;

use LogicException;
use Throwable;

class IntegrationException extends LogicException
{
    public const NOT_FOUND_CODE = 100;
    public const NOT_FOUND_MESSAGE = 'Integration not found';

    public const REFRESH_TOKEN_CODE = 200;
    public const REFRESH_TOKEN_MESSAGE = 'Can not refresh token';

    public static function notFoundException(Throwable $previous = null): self
    {
        return new self(self::NOT_FOUND_MESSAGE, self::NOT_FOUND_CODE, $previous);
    }

    public static function refreshTokenException(Throwable $previous = null): self
    {
        return new self(self::REFRESH_TOKEN_MESSAGE, self::REFRESH_TOKEN_CODE, $previous);
    }
}

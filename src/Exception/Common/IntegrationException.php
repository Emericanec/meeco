<?php

declare(strict_types=1);


namespace App\Exception\Common;

use LogicException;

class IntegrationException extends LogicException
{
    public const NOT_FOUND_CODE = 100;
    public const NOT_FOUND_MESSAGE = 'Integration not found';

    public static function notFoundException(): self
    {
        return new self(self::NOT_FOUND_MESSAGE, self::NOT_FOUND_CODE);
    }
}

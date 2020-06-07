<?php

declare(strict_types=1);

namespace App\Exception\Common;

use LogicException;

class AdminException extends LogicException
{
    public const USER_IS_INVALID_CODE = 100;
    public const USER_IS_INVALID_MESSAGE = 'User is invalid';

    public static function userIsInvalid(): self
    {
        return new self(self::USER_IS_INVALID_MESSAGE, self::USER_IS_INVALID_CODE);
    }
}
<?php

namespace BeesInTheTrap\Exceptions;

class HiveException extends \RuntimeException
{
    public static function noBeeFound(): self
    {
        return new self("No bees found in the hive. The hive appears to be empty!");
    }

    public static function queenNotFound(): self
    {
        return new self("Critical error: Queen bee not found in the hive!");
    }
}

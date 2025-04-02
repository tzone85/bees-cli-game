<?php

namespace BeesInTheTrap\Exceptions;

class GameException extends \RuntimeException
{
    public static function invalidCommand(string $command): self
    {
        return new self(sprintf(
            "Invalid command '%s'. Type 'hit' to attack the hive or use --auto for auto mode.",
            $command
        ));
    }

    public static function gameAlreadyOver(string $reason): self
    {
        return new self(sprintf(
            "Cannot continue the game: %s",
            $reason
        ));
    }
}

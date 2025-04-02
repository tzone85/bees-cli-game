<?php

namespace BeesInTheTrap\Entities;

/**
 * Base class for all our buzzing friends (or enemies, depending on your perspective!)
 */
abstract class Bee
{
    public function __construct(
        protected int $hitPoints,    // How much punishment this bee can take
        protected int $damagePerHit, // How much damage this bee receives when hit
        protected int $stingDamage   // How much pain this bee can dish out
    ) {}

    /**
     * Ouch! Take that, bee!
     */
    public function takeDamage(int $damage): void
    {
        $this->hitPoints = max(0, $this->hitPoints - $damage);
    }

    /**
     * Is this bee still buzzing?
     */
    public function isAlive(): bool
    {
        return $this->hitPoints > 0;
    }

    public function getHitPoints(): int
    {
        return $this->hitPoints;
    }

    public function getStingDamage(): int
    {
        return $this->stingDamage;
    }

    abstract public function getType(): string;
}

<?php

namespace BeesInTheTrap\Entities;

class Player
{
    private int $hitPoints = 100;
    private int $hitCount = 0;
    private int $stingsReceived = 0;

    public function takeDamage(int $damage): void
    {
        $this->hitPoints = max(0, $this->hitPoints - $damage);
        $this->stingsReceived++;
    }

    public function recordHit(): void
    {
        $this->hitCount++;
    }

    public function isAlive(): bool
    {
        return $this->hitPoints > 0;
    }

    public function getHitPoints(): int
    {
        return $this->hitPoints;
    }

    public function getHitCount(): int
    {
        return $this->hitCount;
    }

    public function getStingsReceived(): int
    {
        return $this->stingsReceived;
    }
}

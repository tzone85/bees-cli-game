<?php

namespace BeesInTheTrap\Entities;

class QueenBee extends Bee
{
    public function __construct()
    {
        parent::__construct(
            hitPoints: 100,
            damagePerHit: 10,
            stingDamage: 10
        );
    }

    public function getType(): string
    {
        return 'Queen';
    }
}

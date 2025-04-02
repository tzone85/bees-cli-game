<?php

namespace BeesInTheTrap\Entities;

class DroneBee extends Bee
{
    public function __construct()
    {
        parent::__construct(
            hitPoints: 60,
            damagePerHit: 30,
            stingDamage: 1
        );
    }

    public function getType(): string
    {
        return 'Drone';
    }
}

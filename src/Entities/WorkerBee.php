<?php

namespace BeesInTheTrap\Entities;

class WorkerBee extends Bee
{
    public function __construct()
    {
        parent::__construct(
            hitPoints: 75,
            damagePerHit: 25,
            stingDamage: 5
        );
    }

    public function getType(): string
    {
        return 'Worker';
    }
}

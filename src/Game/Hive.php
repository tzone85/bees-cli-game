<?php

namespace BeesInTheTrap\Game;

use BeesInTheTrap\Entities\{Bee, QueenBee, WorkerBee, DroneBee};
use BeesInTheTrap\Exceptions\HiveException;

class Hive
{
    /** @var array<Bee> */
    private array $bees = [];

    public function __construct()
    {
        $this->bees[] = new QueenBee();
        
        for ($i = 0; $i < 5; $i++) {
            $this->bees[] = new WorkerBee();
        }
        
        for ($i = 0; $i < 25; $i++) {
            $this->bees[] = new DroneBee();
        }
    }

    public function getAliveBees(): array
    {
        return array_filter($this->bees, fn(Bee $bee) => $bee->isAlive());
    }

    /**
     * Get a random alive bee from the hive
     * 
     * @throws HiveException if no alive bees are found
     */
    public function getRandomAliveBee(): Bee
    {
        $aliveBees = $this->getAliveBees();
        if (empty($aliveBees)) {
            throw HiveException::noBeeFound();
        }
        return $aliveBees[array_rand($aliveBees)];
    }

    public function isDestroyed(): bool
    {
        return !$this->getQueenBee()->isAlive();
    }

    /**
     * Get the queen bee from the hive
     * 
     * @throws HiveException if the queen bee is not found
     */
    private function getQueenBee(): QueenBee
    {
        $queen = $this->bees[0] ?? null;
        if (!$queen instanceof QueenBee) {
            throw HiveException::queenNotFound();
        }
        return $queen;
    }
}

<?php

namespace BeesInTheTrap\Game;

use BeesInTheTrap\Entities\{Player, Bee};
use BeesInTheTrap\Exceptions\{GameException, HiveException};

class Game
{
    // Life's not fair - sometimes you just miss!
    private const MISS_CHANCE = 30;

    private array $missMessages = [
        'player' => [
            'Whoosh! The bees dodge your attack!',
            'Miss! Your aim needs some work...',
            'The bees are too quick for you this time!😆'
        ],
        'bees' => [
            'Buzz! That was close! The bees just missed you!',
            'Lucky escape! The bees fly right past you!',
            'You dodge the incoming bees - nice moves!🫸'
        ]
    ];

    public function __construct(
        private Player $player,
        private Hive $hive
    ) {}

    /**
     * @throws GameException if the game is already over
     * @throws HiveException if there's an issue with the hive state
     */
    public function playerTurn(): string
    {
        if ($this->isGameOver()) {
            throw GameException::gameAlreadyOver(
                $this->player->isAlive()
                    ? 'The hive is already destroyed!'
                    : 'You have been defeated!'
            );
        }

        if ($this->shouldMiss()) {
            return $this->missMessages['player'][array_rand($this->missMessages['player'])];
        }

        try {
            $targetBee = $this->hive->getRandomAliveBee();
            $this->player->recordHit();
            $damage = $this->getDamageForBee($targetBee);
            $targetBee->takeDamage($damage);

            $messages = [
                'Queen' => [
                    "BOOM! The Queen takes a direct hit! (%d damage)",
                    "The Queen Bee staggers from your mighty blow! (%d damage)"
                ],
                'Worker' => [
                    "POW! You caught a Worker Bee! (%d damage)",
                    "That Worker Bee won't be working anymore! (%d damage)"
                ],
                'Drone' => [
                    "WHACK! A Drone Bee feels your wrath! (%d damage)",
                    "Another Drone bites the dust! (%d damage)"
                ]
            ];

            $beeType = $targetBee->getType();
            $messageTemplate = $messages[$beeType][array_rand($messages[$beeType])];

            return sprintf($messageTemplate, $damage);
        } catch (HiveException $e) {
            return "The hive seems quiet... no bees in sight!";
        }
    }

    /**
     * @throws GameException if the game is already over
     * @throws HiveException if there's an issue with the hive state
     */
    public function beeTurn(): string
    {
        if ($this->isGameOver()) {
            throw GameException::gameAlreadyOver(
                $this->player->isAlive()
                    ? 'The hive is already destroyed!'
                    : 'You have been defeated!'
            );
        }

        if ($this->shouldMiss()) {
            return $this->missMessages['bees'][array_rand($this->missMessages['bees'])];
        }

        try {
            $attackingBee = $this->hive->getRandomAliveBee();
            $damage = $attackingBee->getStingDamage();
            $this->player->takeDamage($damage);

            $messages = [
                'Queen' => [
                    "OUCH! The Queen's royal sting burns! (%d damage)",
                    "Her Majesty shows you who's boss! (%d damage)"
                ],
                'Worker' => [
                    "ZAP! A Worker Bee caught you! (%d damage)",
                    "That Worker means business! (%d damage)"
                ],
                'Drone' => [
                    "A pesky Drone manages to sting you! (%d damage)",
                    "Tiny sting, but it still hurts! (%d damage)"
                ]
            ];

            $beeType = $attackingBee->getType();
            $messageTemplate = $messages[$beeType][array_rand($messages[$beeType])];

            return sprintf($messageTemplate, $damage);
        } catch (HiveException $e) {
            return "The hive seems quiet... no bees in sight!";
        }
    }

    public function isGameOver(): bool
    {
        return !$this->player->isAlive() || $this->hive->isDestroyed();
    }

    public function getGameOverMessage(): string
    {
        if ($this->hive->isDestroyed()) {
            $messages = [
                "Victory! The hive is no more! It took you %d mighty swings.",
                "The bees have been defeated! %d hits to save the day!",
                "You're the champion bee slayer! Only %d hits needed!"
            ];
            return sprintf(
                $messages[array_rand($messages)],
                $this->player->getHitCount()
            );
        }

        $messages = [
            "Ouch! The bees were too much for you. %d stings ended your journey.",
            "Game Over! The swarm claims another victim after %d stings.",
            "Better luck next time! %d stings proved too many to handle."
        ];
        return sprintf(
            $messages[array_rand($messages)],
            $this->player->getStingsReceived()
        );
    }

    public function getStatus(): string
    {
        $healthStatus = match (true) {
            $this->player->getHitPoints() > 75 => '💪',
            $this->player->getHitPoints() > 50 => '😅',
            $this->player->getHitPoints() > 25 => '😰',
            default => '😱'
        };

        return sprintf(
            "%s Health: %d | Hits Landed: %d | Stings Taken: %d",
            $healthStatus,
            $this->player->getHitPoints(),
            $this->player->getHitCount(),
            $this->player->getStingsReceived()
        );
    }

    protected function shouldMiss(): bool
    {
        return rand(1, 100) <= self::MISS_CHANCE;
    }

    private function getDamageForBee(Bee $bee): int
    {
        return match ($bee->getType()) {
            'Queen' => 10,
            'Worker' => 25,
            'Drone' => 30,
            default => 0,
        };
    }
}

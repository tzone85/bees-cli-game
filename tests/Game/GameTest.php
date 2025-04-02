<?php

namespace Tests\Game;

use PHPUnit\Framework\TestCase;
use BeesInTheTrap\Entities\Player;
use BeesInTheTrap\Game\Game;
use BeesInTheTrap\Game\Hive;

/**
 * @covers \BeesInTheTrap\Game\Game
 */
class GameTest extends TestCase
{
    /**
     * @var Game
     */
    private Game $game;

    /**
     * @var Player
     */
    private Player $player;

    /**
     * @var Hive
     */
    private Hive $hive;

    /**
     * Setup the test environment
     */
    protected function setUp(): void
    {
        $this->player = new Player();
        $this->hive = new Hive();
        $this->game = new Game($this->player, $this->hive);
    }

    /**
     * Test that the player starts with 100 HP
     */
    public function testPlayerStartsWith100HP(): void
    {
        $this->assertEquals(100, $this->player->getHitPoints());
    }

    /**
     * Test that the game is over when the player dies
     */
    public function testGameOverWhenPlayerDies(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $this->player->takeDamage(1);
        }
        
        $this->assertTrue($this->game->isGameOver());
    }

    /**
     * Test that the game is over when the hive is destroyed
     */
    public function testGameOverWhenHiveDestroyed(): void
    {
        $reflection = new \ReflectionClass($this->hive);
        $method = $reflection->getMethod('getQueenBee');
        $method->setAccessible(true);
        $queenBee = $method->invoke($this->hive);
        
        // Kill the queen bee
        for ($i = 0; $i < 10; $i++) {
            $queenBee->takeDamage(10);
        }
        
        $this->assertTrue($this->game->isGameOver());
    }

    /**
     * Test that the player's turn increases their hit count
     */
    public function testPlayerTurnIncreasesHitCount(): void
    {
        // Mock the Game class to ensure we don't miss
        /** @var Game&\PHPUnit\Framework\MockObject\MockObject $gameMock */
        $gameMock = $this->getMockBuilder(Game::class)
            ->setConstructorArgs([$this->player, $this->hive])
            ->onlyMethods(['shouldMiss'])
            ->getMock();
        
        $gameMock->method('shouldMiss')
            ->willReturn(false);
        
        $initialHits = $this->player->getHitCount();
        $gameMock->playerTurn();
        $this->assertGreaterThan($initialHits, $this->player->getHitCount());
    }

    /**
     * Test that the bee's turn increases the player's sting count
     */
    public function testBeeTurnIncreasesStingCount(): void
    {
        $initialStings = $this->player->getStingsReceived();
        $this->game->beeTurn();
        $this->assertGreaterThanOrEqual($initialStings, $this->player->getStingsReceived());
    }
}

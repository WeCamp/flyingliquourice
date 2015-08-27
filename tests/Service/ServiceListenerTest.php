<?php
/**
 * Created by PhpStorm.
 * User: henri
 * Date: 8/26/15
 * Time: 2:28 PM
 */

namespace Wecamp\FlyingLiqourice\Service;

use Rhumsaa\Uuid\Uuid;

class ServiceListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_returns_the_game_id()
    {
        $token = 'START';
        $id = Uuid::uuid4()->toString();
        $service = new ServiceListener($token, $id);
        $this->assertEquals($id, $service->id());
    }

    /**
     * @test
     */
    public function it_creates_a_new_game()
    {
        $token = 'START';
        $service = new ServiceListener($token);

        $this->assertStringStartsWith('STARTED ', $service->run());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_restarts_a_non_existing_game()
    {
        $id = Uuid::uuid4()->toString();
        $token = 'START ' . $id;
        $service = new ServiceListener($token, $id);
        $this->assertStringStartsWith('{"id":"' . $id, $service->run());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_shows_status_a_non_existing_of_game()
    {
        $token = 'STATUS';
        $id = Uuid::uuid4()->toString();
        $service = new ServiceListener($token, $id);
        $this->assertStringStartsWith('{"id":"' . $id, $service->run());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_quits_a_game()
    {
        $id = Uuid::uuid4()->toString();
        $token = 'QUIT';
        $service = new ServiceListener($token, $id);
        $this->assertEquals('"You lost"', $service->run());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_fires_on_a3()
    {
        $id = Uuid::uuid4()->toString();
        $token = 'fire 12.34';
        $service = new ServiceListener($token, $id);
        $this->assertEquals('"Shot has been fired on 12-34"', $service->run());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_runs_a_non_existing_command()
    {
        $id = Uuid::uuid4()->toString();
        $token = 'unknown command';
        $service = new ServiceListener($token, $id);
        $service->run();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function it_runs_non_existing_command_run()
    {
        $id = Uuid::uuid4()->toString();
        $token = 'run';
        $service = new ServiceListener($token, $id);
        $service->run();
    }
}

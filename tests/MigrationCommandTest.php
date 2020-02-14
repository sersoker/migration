<?php
declare(strict_types=1);

namespace PcComponentes\Migration\Tests;

use PcComponentes\Migration\MigrationCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class MigrationCommandTest extends TestCase
{
    private const DIR = __DIR__ . '/../migration';

    private MigrationCommand $command;
    private CommandTester $tester;

    public function setUp(): void
    {
        $this->command = new MigrationCommand('test', self::DIR, []);
        $this->tester = new CommandTester($this->command);
    }

    /**
     * @test
     */
    public function given_a_command_when_execute_it_then_success()
    {
        $this->tester->execute(
            [
                'migrations' => ['MigrationTested'],
                '--operation' => 'down',
            ]
        );

        $this->assertEquals(0, $this->tester->getStatusCode());
    }

    /**
     * @test
     */
    public function given_a_invalid_migration_when_execute_the_command_then_fail()
    {
        $this->tester->execute(
            [
                'migrations' => ['Unkonw'],
                '--operation' => 'down',
            ]
        );

        $this->assertEquals(1, $this->tester->getStatusCode());
    }

    /**
     * @test
     */
    public function given_a_invalid_operation_when_execute_the_command_then_fail()
    {
        $this->tester->execute(
            [
                'migrations' => ['MigrationTested'],
                '--operation' => 'unkonw',
            ]
        );

        $this->assertEquals(1, $this->tester->getStatusCode());
    }
}

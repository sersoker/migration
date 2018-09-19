<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <jgrodriguezcarrion@gmail.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Migration\Tests;

use Pccomponentes\Migration\MigrationExecutor;
use PHPUnit\Framework\TestCase;

class MigrationExecutorTest extends TestCase
{
    private const DIR = __DIR__ . '/../migration';

    public function setUp()
    {
        include_once self::DIR . '/MigrationTested.php';
    }

    /**
     * @test
     */
    public function given_migrations_when_ask_to_do_up_uperation_then_execute_it()
    {
        $migration = new \MigrationTested();
        $executor = new MigrationExecutor([$migration]);
        $executor->upOperation();

        $this->assertTrue($migration->upOperationCalled());
    }

    /**
     * @test
     */
    public function given_migrations_when_ask_to_do_down_uperation_then_execute_it()
    {
        $migration = new \MigrationTested();
        $executor = new MigrationExecutor([$migration]);
        $executor->downOperation();

        $this->assertTrue($migration->downOperationCalled());
    }
}

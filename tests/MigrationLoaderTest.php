<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Migration\Tests;

use Pccomponentes\Migration\MigrationLoader;
use PHPUnit\Framework\TestCase;

class MigrationLoaderTest extends TestCase
{
    private const DIR = __DIR__ . '/../migration';

    /**
     * @test
     */
    public function given_migration_path_when_load_it_then_return_instance()
    {
        $loader = new MigrationLoader(self::DIR, ['example_arg', 5]);
        $migrations = $loader->load(['MigrationTested']);

        $this->assertCount(1, $migrations);
        /** @var $migration \MigrationTested */
        $migration = $migrations[0];
        $this->assertInstanceOf(\MigrationTested::class, $migration);
        $this->assertEquals(['example_arg', 5], $migration->constructorArgs());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function given_migration_unknow_path_when_load_it_then_throw_exception()
    {
        $loader = new MigrationLoader(__DIR__ . '/unknow', []);
        $loader->load(['MigrationTested']);
    }
}

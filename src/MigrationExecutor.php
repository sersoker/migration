<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <juan.rodriguez@pccomponentes.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Migration;

final class MigrationExecutor
{
    private $migrations;

    public function __construct(array $migrations)
    {
        $this->migrations = $migrations;
    }

    public function migrations(): array
    {
        return $this->migrations;
    }

    public function upOperation(): void
    {
        \array_walk(
            $this->migrations,
            function (Migration $migration) {
                $migration->upOperation();
            }
        );
    }

    public function downOperation(): void
    {
        \array_walk(
            $this->migrations,
            function (Migration $migration) {
                $migration->downOperation();
            }
        );
    }
}

<?php
declare(strict_types=1);

namespace PcComponentes\Migration;

final class MigrationExecutor
{
    private array $migrations;

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
            static function (Migration $migration) {
                $migration->upOperation();
            }
        );
    }

    public function downOperation(): void
    {
        \array_walk(
            $this->migrations,
            static function (Migration $migration) {
                $migration->downOperation();
            }
        );
    }
}

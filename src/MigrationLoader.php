<?php
declare(strict_types=1);

namespace PcComponentes\Migration;

final class MigrationLoader
{
    private string $dir;
    private array $migrationArgs;

    public function __construct(string $dir, array $migrationArgs)
    {
        $this->dir = $dir;
        $this->migrationArgs = $migrationArgs;
    }

    public function load(array $migrationClassNames)
    {
        $migrations = [];
        foreach ($migrationClassNames as $migrationClassName) {
            $migrations[] = $this->loadOne($migrationClassName);
        }

        return $migrations;
    }

    private function loadOne(string $className): Migration
    {
        $classFile = \realpath("{$this->dir}/{$className}.php");

        if (false === $classFile) {
            throw new \InvalidArgumentException(
                sprintf('The migration file %s/%s.php dont exist.', $this->dir, $className)
            );
        }

        require_once $classFile;

        return (new \ReflectionClass($className))->newInstanceArgs($this->migrationArgs);
    }
}

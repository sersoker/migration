<?php
/**
 * This disaster was designed by
 * @author Juan G. Rodríguez Carrión <jgrodriguezcarrion@gmail.com>
 */
declare(strict_types=1);
namespace Pccomponentes\Migration;

final class MigrationLoader
{
    private $dir;
    private $migrationArgs;

    public function __construct(string $dir, array $migrationArgs)
    {
        $this->dir = $dir;
        $this->migrationArgs = $migrationArgs;
    }

    public function load(array $migrationClassNames)
    {
        return \array_map(
            [$this, 'loadOne'],
            $migrationClassNames
        );
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

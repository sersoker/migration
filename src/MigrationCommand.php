<?php
declare(strict_types=1);

namespace PcComponentes\Migration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrationCommand extends Command
{
    private MigrationLoader $loader;

    private const OPERATION_UP = 'up';
    private const OPERATION_DOWN = 'down';

    public function __construct(string $commandName, string $migrationDir, array $migrationArgs)
    {
        parent::__construct("migration:{$commandName}");

        $this->loader = new MigrationLoader($migrationDir, $migrationArgs);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Do "UP" operation over a specified migration files.')
            ->addOption(
                'operation',
                'op',
                InputOption::VALUE_REQUIRED,
                sprintf('Available operations: %s, %s', self::OPERATION_UP, self::OPERATION_DOWN)
            )
            ->addArgument(
                'migrations',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Who migration classes do you want to migrate (separate multiple names with a space)?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $operation = $input->getOption('operation');
            $executor = new MigrationExecutor(
                $this->loader->load($input->getArgument('migrations'))
            );
            switch ($operation) {
                case self::OPERATION_UP:
                    $executor->upOperation();
                    return 0;
                case self::OPERATION_DOWN:
                    $executor->downOperation();
                    return 0;
                default:
                    $output->writeln(sprintf('<error>Invalid operation %s</error>', $operation));
                    $output->write('<comment>Available operations: </comment>');
                    $output->writeln(sprintf('<options=bold>%s, %s</>', self::OPERATION_UP, self::OPERATION_DOWN));
                    return 1;
            }
        } catch (\Throwable $e) {
            $output->writeln(sprintf('<error>Exception: %s</error>', $e->getMessage()));
            return 1;
        }
    }
}

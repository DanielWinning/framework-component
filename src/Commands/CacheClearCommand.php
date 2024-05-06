<?php

namespace Luma\Framework\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheClearCommand extends Command
{
    protected static string $defaultName = 'luma:cache:clear';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Clears cached files.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cacheDirectory = sprintf('%s/cache', dirname(__DIR__, 5));

        if (file_exists($cacheDirectory)) {
            $files = glob($cacheDirectory . '/{,.}[!.,!..]*', GLOB_BRACE | GLOB_MARK);

            foreach ($files as $file) {
                if (is_file($file) && basename($file) !== '.gitignore') {
                    unlink($file);
                }
            }

            $output->writeln('Cache cleared successfully.');
        } else {
            $output->writeln('Cache directory does not exist.');
        }

        return Command::SUCCESS;
    }
}
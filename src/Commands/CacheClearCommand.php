<?php

namespace Luma\Framework\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'luma:cache:clear', description: 'Clears cached files.')]
class CacheClearCommand extends Command
{
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
        $style = new SymfonyStyle($input, $output);
        $cacheDirectory = sprintf('%s/cache', dirname(__DIR__, 5));

        if (file_exists($cacheDirectory)) {
            $directory = new \RecursiveDirectoryIterator($cacheDirectory, \FilesystemIterator::SKIP_DOTS);
            $iterator = new \RecursiveIteratorIterator($directory);
            $files = new \RegexIterator($iterator, '/^.+(\.php|\.php\.lock)$/i', \RegexIterator::GET_MATCH);

            foreach ($files as $file) {
                $filePath = $file[0];

                if (basename($filePath) !== '.gitignore') {
                    unlink($filePath);
                }
            }

            $style->success('All caches cleared successfully.');
        } else {
            $style->error('Cache directory does not exist.');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
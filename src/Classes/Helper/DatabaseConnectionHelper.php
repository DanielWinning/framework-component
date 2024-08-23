<?php

namespace Luma\Framework\Classes\Helper;

use Dotenv\Dotenv;
use Luma\AuroraDatabase\DatabaseConnection;
use Luma\AuroraDatabase\Model\Aurora;

class DatabaseConnectionHelper
{
    /**
     * @return void
     *
     * @throws \Exception
     */
    public static function connect(): void
    {
        $configDirectory = sprintf('%s/config', dirname(__DIR__, 6));
        $dotenv = Dotenv::createImmutable($configDirectory);
        $dotenv->load();

        if (!isset($_ENV['DATABASE_HOST']) || !isset($_ENV['DATABASE_PORT']) || !isset($_ENV['DATABASE_USER']) || !isset($_ENV['DATABASE_PASSWORD'])) {
            throw new \RuntimeException('Missing credentials. Please set database credentials in your applications .env file');
        }

        $connection = new DatabaseConnection(
            sprintf('mysql:host=%s;port=%d;', $_ENV['DATABASE_HOST'], $_ENV['DATABASE_PORT']),
            $_ENV['DATABASE_USER'],
            $_ENV['DATABASE_PASSWORD']
        );
        Aurora::setDatabaseConnection($connection);
    }
}
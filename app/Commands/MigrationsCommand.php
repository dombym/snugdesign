<?php

declare(strict_types=1);

namespace App\Commands;

use Nextras\Dbal\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PDOException;

#[AsCommand(name: 'app:migrations', description: 'Run all SQL migrations')]
final class MigrationsCommand extends Command
{
    private Connection $connection;
    private array $folders;

    public function __construct()
    {
        parent::__construct();

        $this->folders = [
            __DIR__ . '/../../migrations/structures',
            __DIR__ . '/../../migrations/basic-data',
            __DIR__ . '/../../migrations/dummy-data',
        ];
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host     = getenv('DB_HOST') ?: '127.0.0.1';
        $user     = getenv('DB_USER') ?: '';
        $password = getenv('DB_PASSWORD') ?: '';
        $database = getenv('DB_NAME') ?: '';
        $port     = getenv('DB_PORT') ?: '3306';

        $output->writeln("Waiting for MySQL to be available...");
        $attempts = 0;
        $maxAttempts = 30;
        while (true) {
            try {
                $this->connection = new Connection([
                    'driver'   => 'mysqli',
                    'host'     => $host,
                    'port'     => $port,
                    'username' => $user,
                    'password' => $password,
                    'database' => $database,
                    'charset'  => 'utf8mb4',
                ]);
                $this->connection->connect();
                break;
            } catch (\Throwable $e) {
                $attempts++;
                if ($attempts >= $maxAttempts) {
                    $output->writeln("<error>Cannot connect to MySQL after $maxAttempts attempts.</error>");
                    return Command::FAILURE;
                }
                sleep(1);
            }
        }

        $output->writeln("MySQL is available, starting migrations...");

        // Spuštění migrací
        foreach ($this->folders as $folder) {
            $output->writeln("Processing folder: $folder");

            $files = glob($folder . '/*.sql');
            sort($files);

            foreach ($files as $file) {
                $output->writeln("Executing SQL file: $file");
                $sql = file_get_contents($file);

                $queries = array_filter(array_map('trim', explode(';', $sql)));

                foreach ($queries as $query) {
                    if ($query === '') continue;

                    $output->writeln("Executing query: " . substr($query, 0, 60) . '...');
                    $this->connection->query('%raw', $query);
                }
            }
        }

        $output->writeln("All migrations executed successfully.");
        return Command::SUCCESS;
    }
}

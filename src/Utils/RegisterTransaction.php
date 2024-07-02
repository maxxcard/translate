<?php

declare(strict_types=1);

namespace App\Utils;

use App\Actions\Aux\Config;

class RegisterTransaction
{
    private const CREATE_TRANSACTION_TABLE = <<<EOL
CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    converted_to TEXT NOT NULL,
    converted_from TEXT NOT NULL,
    created_date TEXT NOT NULL
)
EOL;

    private const INSERT = "INSERT INTO transactions (converted_to, converted_from, created_date) VALUES (?, ?, ?)";

    private const EXISTS = "SELECT * FROM transactions WHERE converted_from = ?";

    public function __construct(
        private readonly Config $config
    )
    {

    }

    public function create(string $convertToFileName, string $convertedFromFileName): bool
    {
        if (!$this->tableExists()) {
            $this->createTable();
        }

        $conn = $this->connection();
        $now = (new \DateTimeImmutable())->format("Y-m-d H:i:s");

        return $conn->prepare(self::INSERT)->execute([$convertToFileName, $convertedFromFileName, $now]);
    }

    public function transactionExists(string $convertedFromFileName): bool
    {
        $conn = $this->connection();
        $prepare = $conn->prepare(self::EXISTS);
        $prepare->execute([$convertedFromFileName]);

        return count($prepare->fetchAll(\PDO::FETCH_ASSOC)) !== 0;
    }

    private function connection(): \PDO
    {
        $dbPath = $this->config->getConfig('db_path');
        $dns = sprintf('sqlite:%s/db.db', $dbPath);

        return new \PDO($dns);
    }

    private function tableExists(): bool
    {
        try {
            $this->connection()->prepare('SELECT 1 FROM transactions LIMIT 1');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function createTable(): void
    {
        $this->connection()->query(self::CREATE_TRANSACTION_TABLE);
    }
}
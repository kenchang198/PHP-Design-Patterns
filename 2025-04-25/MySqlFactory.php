<?php

namespace App\Patterns\AbstractFactory;

require_once 'DatabaseFactory.php';

// MySQL接続の具体的実装
class MySqlConnection implements Connection
{
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private bool $connected = false;

    public function __construct(string $host = 'localhost', string $username = 'root', string $password = '', string $database = 'test')
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    public function connect(): bool
    {
        // 実際のデータベース接続処理はここに実装します
        // ここではシミュレーションとして成功したことにします
        $this->connected = true;
        return true;
    }

    public function disconnect(): bool
    {
        $this->connected = false;
        return true;
    }

    public function getConnectionInfo(): string
    {
        return sprintf(
            "MySQL Connection to %s@%s/%s (Status: %s)",
            $this->username,
            $this->host,
            $this->database,
            $this->connected ? 'Connected' : 'Disconnected'
        );
    }
}

// MySQLクエリビルダーの具体的実装
class MySqlQueryBuilder implements QueryBuilder
{
    public function select(string $table, array $fields): string
    {
        return sprintf(
            "SELECT %s FROM %s;",
            implode(', ', $fields),
            $table
        );
    }

    public function insert(string $table, array $data): string
    {
        return sprintf(
            "INSERT INTO %s (%s) VALUES (%s);",
            $table,
            implode(', ', array_keys($data)),
            implode(', ', array_map(function ($value) {
                return is_string($value) ? "'$value'" : $value;
            }, array_values($data)))
        );
    }

    public function update(string $table, array $data, string $condition): string
    {
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = sprintf(
                "%s = %s",
                $key,
                is_string($value) ? "'$value'" : $value
            );
        }

        return sprintf(
            "UPDATE %s SET %s WHERE %s;",
            $table,
            implode(', ', $setParts),
            $condition
        );
    }

    public function delete(string $table, string $condition): string
    {
        return sprintf(
            "DELETE FROM %s WHERE %s;",
            $table,
            $condition
        );
    }
}

// MySQLデータベースファクトリの具体的実装
class MySqlFactory implements DatabaseFactory
{
    public function createConnection(): Connection
    {
        return new MySqlConnection();
    }

    public function createQueryBuilder(): QueryBuilder
    {
        return new MySqlQueryBuilder();
    }
}

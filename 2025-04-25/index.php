<?php

namespace App\Patterns\AbstractFactory;

require_once 'MySqlFactory.php';
require_once 'PostgreSqlFactory.php';

/**
 * データベース操作を行うクライアントクラス
 * どのデータベースを使うかは実行時に指定可能
 */
class DatabaseClient
{
    private Connection $connection;
    private QueryBuilder $queryBuilder;

    public function __construct(DatabaseFactory $factory)
    {
        $this->connection = $factory->createConnection();
        $this->queryBuilder = $factory->createQueryBuilder();
    }

    public function connect(): bool
    {
        return $this->connection->connect();
    }

    public function disconnect(): bool
    {
        return $this->connection->disconnect();
    }

    public function getConnectionInfo(): string
    {
        return $this->connection->getConnectionInfo();
    }

    public function executeQuery(string $table, array $fields): string
    {
        $sql = $this->queryBuilder->select($table, $fields);
        // 実際にはここでクエリを実行します
        return "Executing: $sql";
    }
    
    public function insertData(string $table, array $data): string
    {
        $sql = $this->queryBuilder->insert($table, $data);
        // 実際にはここでクエリを実行します
        return "Executing: $sql";
    }
}

// 設定に応じて適切なファクトリを使用（環境変数、設定ファイルなどから取得可能）
$dbType = 'mysql'; // または 'postgres'

if ($dbType === 'mysql') {
    $factory = new MySqlFactory();
} else {
    $factory = new PostgreSqlFactory();
}

// クライアントコードはどのデータベースを使うか知らなくても良い
$dbClient = new DatabaseClient($factory);
$dbClient->connect();

echo $dbClient->getConnectionInfo() . PHP_EOL;
echo $dbClient->executeQuery('users', ['id', 'name', 'email']) . PHP_EOL;
echo $dbClient->insertData('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'age' => 30
]) . PHP_EOL;

// PostgreSQLに切り替えるのも簡単
$postgresClient = new DatabaseClient(new PostgreSqlFactory());
$postgresClient->connect();

echo $postgresClient->getConnectionInfo() . PHP_EOL;
echo $postgresClient->executeQuery('customers', ['id', 'name', 'email']) . PHP_EOL;

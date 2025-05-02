<?php

namespace App\Patterns\AbstractFactory;

// データベースコネクションのインターフェース
interface Connection
{
    public function connect(): bool;
    public function disconnect(): bool;
    public function getConnectionInfo(): string;
}

// クエリビルダーのインターフェース
interface QueryBuilder
{
    public function select(string $table, array $fields): string;
    public function insert(string $table, array $data): string;
    public function update(string $table, array $data, string $condition): string;
    public function delete(string $table, string $condition): string;
}

// データベースファクトリのインターフェース
interface DatabaseFactory
{
    public function createConnection(): Connection;
    public function createQueryBuilder(): QueryBuilder;
}

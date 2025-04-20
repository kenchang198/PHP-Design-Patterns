<?php

require_once 'Logger.php';
require_once 'DatabaseManager.php';

// Singletonパターンの実証: Loggerの利用
$logger = Logger::getInstance();
$logger->setLogLevel(Logger::LOG_LEVEL_DEBUG);
$logger->info("Singletonパターンの実証を開始します");

// 別のLoggerインスタンスを取得（同じインスタンスが返される）
$anotherLogger = Logger::getInstance();
if ($logger === $anotherLogger) {
    echo "Loggerは同じインスタンスです - Singletonパターンが機能しています\n";
}

// DatabaseManagerの利用
$logger->info("DatabaseManagerの利用例");

// Singletonを使ってDatabaseManagerインスタンスを取得
$dbManager = DatabaseManager::getInstance();

// 設定を更新
$dbManager->setConfig([
    'host' => 'mysql',           // Dockerのサービス名
    'database' => 'sample_db',
    'username' => 'sample_user',
    'password' => 'sample_password'
]);

// Singletonパターンの利点を示す：別クラスからの利用
class UserService {
    public function getUsers() {
        // どこからでも同じインスタンスにアクセス
        $dbManager = DatabaseManager::getInstance();
        $users = $dbManager->fetchAll("SELECT * FROM users");
        
        // 同様にLoggerにもアクセス
        $logger = Logger::getInstance();
        $logger->info("UserServiceからユーザー情報を取得: " . count($users) . "件");
        
        return $users;
    }
}

try {
    // データベース接続テスト
    $logger->info("データベース接続を試みます...");
    
    // ユーザーサービスを利用（接続は初回のみ確立される）
    $userService = new UserService();
    $users = $userService->getUsers();
    
    // 直接dbManagerを使った場合（同じ接続が再利用される）
    $products = $dbManager->fetchAll("SELECT * FROM products WHERE price > ?", [20000]);
    $logger->info("高額商品数: " . count($products) . "件");
    
    // Singletonの確認 - 2つのインスタンスが同一であることを確認
    $dbManager2 = DatabaseManager::getInstance();
    if ($dbManager === $dbManager2) {
        $logger->info("✅ DatabaseManagerは同じインスタンスです - Singletonパターンが機能しています");
    } else {
        $logger->error("❌ DatabaseManagerが同じインスタンスではありません - 設計に問題があります");
    }
    
    $logger->info("テスト完了");
    echo "ログファイルを確認してください: " . $logger->getLogFilePath() . PHP_EOL;
    
} catch (Exception $e) {
    $logger->error("エラー発生: " . $e->getMessage());
    $logger->warning("Docker環境が起動していない場合は接続に失敗します");
    $logger->info("Dockerコンテナを起動するには: cd /Users/ken/dev/PHP-Design-Patterns && docker-compose up -d");
    
    echo "エラーが発生しました。ログを確認してください: " . $logger->getLogFilePath() . PHP_EOL;
}

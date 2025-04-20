<?php

class DatabaseManager
{
    // 唯一のインスタンス
    private static $instance = null;
    
    // データベース接続インスタンス
    private $connection = null;
    
    // 接続設定
    private $config = [];
    
    // コンストラクタをprivateに
    private function __construct() {
        // デフォルト設定
        $this->config = [
            'host' => 'mysql',
            'database' => 'sample_db',
            'username' => 'sample_user',
            'password' => 'sample_password',
            'port' => 3306,
            'charset' => 'utf8mb4'
        ];
    }
    
    // cloneを禁止
    private function __clone() {}
    
    // unserializeを禁止
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
    
    // インスタンスを取得
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    // 設定を更新
    public function setConfig(array $config) {
        $this->config = array_merge($this->config, $config);
        $this->connection = null; // 設定変更時に接続をリセット
        return $this;
    }
    
    // 接続を取得（シングルトンパターンで実装）
    public function getConnection() {
        if ($this->connection === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;port=%s;charset=%s',
                    $this->config['host'],
                    $this->config['database'],
                    $this->config['port'],
                    $this->config['charset']
                );
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                $this->connection = new PDO(
                    $dsn, 
                    $this->config['username'], 
                    $this->config['password'], 
                    $options
                );
                
                // 接続確立時のログ
                if (class_exists('Logger')) {
                    $logger = Logger::getInstance();
                    $logger->info("データベース接続が確立されました");
                }
            } catch (PDOException $e) {
                throw new Exception("データベース接続エラー: " . $e->getMessage());
            }
        }
        
        return $this->connection;
    }
    
    // クエリ実行ヘルパーメソッド
    public function query($sql, $params = []) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    // 単一行取得
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    // 複数行取得
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    // 設定値の取得
    public function getConfig($key = null) {
        if ($key === null) {
            return $this->config;
        }
        
        return $this->config[$key] ?? null;
    }
    
    // 接続をクローズ
    public function closeConnection() {
        $this->connection = null;
        return $this;
    }
}

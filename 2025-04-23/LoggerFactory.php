<?php

// ロガーインターフェース
interface Logger {
    public function log($message);
}

// ファイルロガークラス
class FileLogger implements Logger {
    private $filename;
    
    public function __construct($filename = 'application.log') {
        $this->filename = $filename;
    }
    
    public function log($message) {
        echo "ファイル ({$this->filename}) にログを記録: {$message}\n";
        // 実際の実装では、以下のようにファイルに書き込む
        // file_put_contents($this->filename, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    }
}

// データベースロガークラス
class DatabaseLogger implements Logger {
    private $table;
    
    public function __construct($table = 'logs') {
        $this->table = $table;
    }
    
    public function log($message) {
        echo "データベース ({$this->table}テーブル) にログを記録: {$message}\n";
        // 実際の実装では、データベースに接続して挿入する
        // $db->query("INSERT INTO {$this->table} (message, created_at) VALUES ('$message', NOW())");
    }
}

// メールロガークラス
class EmailLogger implements Logger {
    private $recipient;
    
    public function __construct($recipient = 'admin@example.com') {
        $this->recipient = $recipient;
    }
    
    public function log($message) {
        echo "メール ({$this->recipient}) にログを送信: {$message}\n";
        // 実際の実装では、メールを送信する
        // mail($this->recipient, 'Log Message', $message);
    }
}

// 抽象ロガーファクトリークラス
abstract class LoggerFactory {
    // ファクトリーメソッド
    abstract public function createLogger(): Logger;
    
    // ログ記録を実行するテンプレートメソッド
    public function logMessage($message) {
        $logger = $this->createLogger();
        return $logger->log($message);
    }
}

// ファイルロガーファクトリー
class FileLoggerFactory extends LoggerFactory {
    private $filename;
    
    public function __construct($filename = 'application.log') {
        $this->filename = $filename;
    }
    
    public function createLogger(): Logger {
        return new FileLogger($this->filename);
    }
}

// データベースロガーファクトリー
class DatabaseLoggerFactory extends LoggerFactory {
    private $table;
    
    public function __construct($table = 'logs') {
        $this->table = $table;
    }
    
    public function createLogger(): Logger {
        return new DatabaseLogger($this->table);
    }
}

// メールロガーファクトリー
class EmailLoggerFactory extends LoggerFactory {
    private $recipient;
    
    public function __construct($recipient = 'admin@example.com') {
        $this->recipient = $recipient;
    }
    
    public function createLogger(): Logger {
        return new EmailLogger($this->recipient);
    }
}

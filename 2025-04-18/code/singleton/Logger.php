<?php

class Logger 
{
    // ログレベルを定数として定義
    public const LOG_LEVEL_DEBUG = 'DEBUG';
    public const LOG_LEVEL_INFO = 'INFO';
    public const LOG_LEVEL_WARNING = 'WARNING';
    public const LOG_LEVEL_ERROR = 'ERROR';
    
    // 唯一のインスタンスを保持する静的プロパティ
    private static $instance = null;
    
    // ログファイルのパス
    private $logFile;
    
    // 現在のログレベル（どのレベル以上を記録するか）
    private $currentLogLevel;
    
    // ログレベルの優先度マップ（数値が大きいほど重要）
    private $logLevelPriority;
    
    // ログフォーマット
    private $logFormat = "[:datetime] [:level] :message";
    
    // コンストラクタをprivateにして外部からのインスタンス化を防止
    private function __construct() {
        $this->logFile = __DIR__ . '/application.log';
        
        // ログレベルの優先度を設定
        $this->logLevelPriority = [
            self::LOG_LEVEL_DEBUG => 0,   // 最も低い優先度
            self::LOG_LEVEL_INFO => 1,
            self::LOG_LEVEL_WARNING => 2,
            self::LOG_LEVEL_ERROR => 3    // 最も高い優先度
        ];
        
        // デフォルトは全てのログを記録
        $this->currentLogLevel = self::LOG_LEVEL_DEBUG;
    }
    
    // cloneを禁止
    private function __clone() {}
    
    // unserializeを禁止 (PHP 8.0対応)
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }
    
    // インスタンスを取得するメソッド
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    // 記録するログレベルを設定
    public function setLogLevel($level) {
        if (array_key_exists($level, $this->logLevelPriority)) {
            $this->currentLogLevel = $level;
            return true;
        }
        return false;
    }
    
    // 特定のレベルのログを記録すべきかを判断
    private function shouldLog($level) {
        return $this->logLevelPriority[$level] >= $this->logLevelPriority[$this->currentLogLevel];
    }
    
    // ログフォーマットを設定
    public function setLogFormat($format) {
        $this->logFormat = $format;
        return $this;
    }
    
    // ログメッセージのフォーマット処理
    private function formatLogMessage($level, $message) {
        $replacements = [
            ':datetime' => date('Y-m-d H:i:s'),
            ':level' => $level,
            ':message' => $message
        ];
        
        $formattedMessage = $this->logFormat;
        
        foreach ($replacements as $placeholder => $value) {
            $formattedMessage = str_replace($placeholder, $value, $formattedMessage);
        }
        
        return $formattedMessage . PHP_EOL;
    }
    
    // 汎用ログメソッド
    public function log($message, $level = self::LOG_LEVEL_INFO) {
        if (!array_key_exists($level, $this->logLevelPriority)) {
            throw new InvalidArgumentException("不正なログレベル: $level");
        }
        
        if (!$this->shouldLog($level)) {
            return false;
        }
        
        try {
            $logMessage = $this->formatLogMessage($level, $message);
            
            $result = file_put_contents($this->logFile, $logMessage, FILE_APPEND);
            
            if ($result === false) {
                throw new RuntimeException("ログの書き込みに失敗しました: {$this->logFile}");
            }
            
            return true;
        } catch (Exception $e) {
            // エラー処理
            return false;
        }
    }
    
    // 各レベル専用のヘルパーメソッド
    public function debug($message) {
        return $this->log($message, self::LOG_LEVEL_DEBUG);
    }
    
    public function info($message) {
        return $this->log($message, self::LOG_LEVEL_INFO);
    }
    
    public function warning($message) {
        return $this->log($message, self::LOG_LEVEL_WARNING);
    }
    
    public function error($message) {
        return $this->log($message, self::LOG_LEVEL_ERROR);
    }
    
    // ログファイルのパスを取得
    public function getLogFilePath() {
        return $this->logFile;
    }
}
<?php

// ロガーファクトリークラスのインポート
require_once 'LoggerFactory.php';

// ログ処理関数
function logActivity($loggerType, $message, $options = []) {
    $factory = null;
    
    switch ($loggerType) {
        case 'file':
            $filename = $options['filename'] ?? 'application.log';
            $factory = new FileLoggerFactory($filename);
            break;
        case 'database':
            $table = $options['table'] ?? 'logs';
            $factory = new DatabaseLoggerFactory($table);
            break;
        case 'email':
            $recipient = $options['recipient'] ?? 'admin@example.com';
            $factory = new EmailLoggerFactory($recipient);
            break;
        default:
            throw new Exception("サポートされていないロガータイプ: {$loggerType}");
    }
    
    $factory->logMessage($message);
}

// テスト実行
try {
    echo "=== ロギング例のデモ ===\n";
    
    // 標準のファイルロガー
    logActivity('file', 'ユーザーがログインしました');
    
    // カスタムファイル名を指定したファイルロガー
    logActivity('file', 'データがインポートされました', ['filename' => 'import.log']);
    
    // データベースロガー
    logActivity('database', '管理者がシステム設定を変更しました');
    
    // カスタムテーブル名を指定したデータベースロガー
    logActivity('database', 'バックアップが完了しました', ['table' => 'system_logs']);
    
    // メールロガー
    logActivity('email', '重大なエラーが発生しました');
    
    // カスタム受信者を指定したメールロガー
    logActivity('email', 'セキュリティ警告: 複数回のログイン失敗を検出', ['recipient' => 'security@example.com']);
    
    // エラーケース
    // logActivity('sms', 'これは動作しません');
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}

<?php

require_once 'Logger.php';

// Loggerのインスタンスを取得
$logger = Logger::getInstance();

echo "=== DEBUGレベルを含むすべてのログを記録 ===" . PHP_EOL;
// デフォルトはDEBUGレベル (すべてのログを記録)
$logger->debug("デバッグ情報: 変数の値 = 123");
$logger->info("アプリケーションが起動しました");
$logger->warning("ディスク容量が少なくなっています");
$logger->error("データベース接続エラーが発生しました");

echo PHP_EOL . "=== INFOレベル以上のみを記録 ===" . PHP_EOL;
// INFOレベル以上のみを記録する設定に変更
$logger->setLogLevel(Logger::LOG_LEVEL_INFO);
$logger->debug("このメッセージは記録されません"); // 記録されない
$logger->info("このメッセージは記録されます");    // 記録される

echo PHP_EOL . "=== カスタムフォーマットでログを記録 ===" . PHP_EOL;
// カスタムフォーマットを設定
$logger->setLogFormat("[時刻:datetime] レベル=:level | :message");
$logger->error("カスタムフォーマットでのエラーメッセージ");

// 別の場所でも同じインスタンスを使用
$anotherLogger = Logger::getInstance();
$anotherLogger->warning("別のロガーからの警告メッセージ");

// 同じインスタンスかどうか確認
if ($logger === $anotherLogger) {
    echo PHP_EOL . "✅ 同じインスタンスです！" . PHP_EOL;
} else {
    echo PHP_EOL . "❌ 異なるインスタンスです！" . PHP_EOL;
}

// ログレベルの設定も共有されていることを確認
echo "現在のログレベル設定とフォーマットはすべてのインスタンス間で共有されています" . PHP_EOL;

// 汎用メソッドでのログ記録
$logger->log("汎用メソッドでのメッセージ", Logger::LOG_LEVEL_WARNING);

// ログファイルのパスを表示
echo PHP_EOL . "ログファイル: " . $logger->getLogFilePath() . PHP_EOL;

// ログファイルの内容を確認するメッセージ
echo "ログファイルの内容を確認してください: " . $logger->getLogFilePath() . PHP_EOL;
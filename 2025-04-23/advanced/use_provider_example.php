<?php

require_once 'PaymentFactoryProvider.php';

echo "=== Factory Methodパターンの高度な実装例 ===\n\n";

// =========================================
// 設定ベースの動的ファクトリーの例
// =========================================
echo "【設定ベースの動的ファクトリーの例】\n";

// 設定ファイルから読み込む
$configFile = __DIR__ . '/payment_config.json';
if (PaymentFactoryProvider::loadConfig($configFile)) {
    echo "設定ファイルを読み込みました: " . $configFile . "\n";
} else {
    echo "設定ファイルの読み込みに失敗しました。デフォルト設定を使用します。\n";
}

// 動的に新しい決済方法を追加
PaymentFactoryProvider::addFactoryType(
    'apple_pay',
    BitcoinFactory::class,  // 既存のファクトリーを流用（実際には専用クラスを作るべき）
    ['wallet_address' => 'apple_pay_merchant_id']
);

/**
 * 決済を処理する関数
 * 設定ファイルを変更するだけで新しい決済方法に対応！
 */
function processOrderWithProvider($paymentType, $amount) {
    try {
        $factory = PaymentFactoryProvider::getFactory($paymentType);
        return $factory->pay($amount);
    } catch (Exception $e) {
        echo "エラー: " . $e->getMessage() . "\n";
        // 利用可能な決済方法を表示
        $availableTypes = PaymentFactoryProvider::getAvailableTypes();
        echo "利用可能な決済方法: " . implode(', ', $availableTypes) . "\n";
        return false;
    }
}

// 使用例
echo "\n決済処理を実行します:\n";
processOrderWithProvider('credit_card', 5000);
processOrderWithProvider('paypal', 3000);
processOrderWithProvider('bank_transfer', 10000);
processOrderWithProvider('bitcoin', 2000);
processOrderWithProvider('apple_pay', 7000);

// エラーケース
echo "\nエラーケース:\n";
processOrderWithProvider('unknown_method', 1000); 
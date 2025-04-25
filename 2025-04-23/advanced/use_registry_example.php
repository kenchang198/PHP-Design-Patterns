<?php

require_once 'PaymentFactoryRegistry.php';

echo "=== Factory Methodパターンの高度な実装例 ===\n\n";

// =========================================
// レジストリパターンの例
// =========================================
echo "【レジストリパターンの例】\n";

// 初期設定としてファクトリーを登録
PaymentFactoryRegistry::register('credit_card', new CreditCardFactory('registry_api_key_123'));
PaymentFactoryRegistry::register('paypal', new PayPalFactory('registry_client_456', 'registry_secret_789'));
PaymentFactoryRegistry::register('bank_transfer', new BankTransferFactory('REGISTRY_BANK', '87654321'));

// 新しい決済方法を簡単に追加（アプリケーションのプラグイン機能など）
PaymentFactoryRegistry::register('bitcoin', new BitcoinFactory('registry_wallet_address'));

/**
 * 決済を処理する関数
 * 新しい決済方法が追加されても変更不要！
 */
function processOrderWithRegistry($paymentType, $amount) {
    try {
        $factory = PaymentFactoryRegistry::getFactory($paymentType);
        return $factory->pay($amount);
    } catch (Exception $e) {
        echo "エラー: " . $e->getMessage() . "\n";
        // 利用可能な決済方法を表示
        $availableTypes = PaymentFactoryRegistry::getAvailableTypes();
        echo "利用可能な決済方法: " . implode(', ', $availableTypes) . "\n";
        return false;
    }
}

// 使用例
echo "\n決済処理を実行します:\n";
processOrderWithRegistry('credit_card', 5000);
processOrderWithRegistry('paypal', 3000);
processOrderWithRegistry('bank_transfer', 10000);
processOrderWithRegistry('bitcoin', 2000);

// エラーケース
echo "\nエラーケース:\n";
processOrderWithRegistry('unknown_method', 1000);
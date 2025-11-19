<?php

require_once __DIR__ . '/../PaymentStrategy.php';

// ============================================================
// 5. エラーハンドリングのデモ
// ============================================================
echo "【5】エラーハンドリング: 戦略が設定されていない場合\n";
echo str_repeat("=", 60) . "\n\n";

$paymentContext3 = new PaymentContext();

try {
    echo "戦略を設定せずに決済を試みます...\n";
    $paymentContext3->executePayment(5000);
} catch (Exception $e) {
    echo "✗ エラーが発生しました: " . $e->getMessage() . "\n\n";
}

echo "戦略を設定してから再試行...\n";
$paymentContext3->setStrategy(new BitcoinStrategy());
$paymentContext3->executePayment(5000);

echo str_repeat("─", 60) . "\n\n";


<?php

require_once __DIR__ . '/../PaymentStrategy.php';

// ============================================================
// 4. コンストラクタ注入のパターン
// ============================================================
echo "【4】コンストラクタ注入による戦略の設定\n";
echo str_repeat("=", 60) . "\n\n";

echo "▼ 初期戦略を指定してContextを作成\n";
$paymentContext2 = new PaymentContext(new CreditCardStrategy());
$paymentContext2->executePayment(15000);

echo "▼ 後から戦略を変更\n";
$paymentContext2->setStrategy(new PayPalStrategy());
$paymentContext2->executePayment(25000);

echo str_repeat("─", 60) . "\n\n";


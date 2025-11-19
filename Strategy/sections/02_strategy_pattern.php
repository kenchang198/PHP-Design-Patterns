<?php

require_once __DIR__ . '/../PaymentStrategy.php';

// ============================================================
// 2. After: Strategy パターンによる実装
// ============================================================
echo "【2】After: Strategy パターンによる実装\n";
echo str_repeat("=", 60) . "\n\n";

// Contextインスタンスを作成
$paymentContext = new PaymentContext();

// クレジットカード決済
echo "▼ シナリオ1: クレジットカード決済\n";
$creditCard = new CreditCardStrategy('1234-5678-9012-3456', '123');
$paymentContext->setStrategy($creditCard);
$paymentContext->executePayment(10000);

// PayPal決済に切り替え
echo "▼ シナリオ2: PayPal決済に切り替え\n";
$paypal = new PayPalStrategy('customer@example.com');
$paymentContext->setStrategy($paypal);
$paymentContext->executePayment(20000);

// 銀行振込に切り替え
echo "▼ シナリオ3: 銀行振込に切り替え\n";
$bankTransfer = new BankTransferStrategy('0005', '9876543');
$paymentContext->setStrategy($bankTransfer);
$paymentContext->executePayment(30000);

// Bitcoin決済に切り替え
echo "▼ シナリオ4: Bitcoin決済に切り替え\n";
$bitcoin = new BitcoinStrategy('3J98t1WpEZ73CNmYviecrnyiWrnqRhWNLy');
$paymentContext->setStrategy($bitcoin);
$paymentContext->executePayment(50000);

echo "改善点:\n";
echo "  ✓ 新しい決済方法は新しいクラスを追加するだけ(開放閉鎖原則)\n";
echo "  ✓ 実行時に戦略を動的に切り替え可能\n";
echo "  ✓ 各戦略が独立してテスト可能\n";
echo "  ✓ 単一責任の原則を遵守\n";
echo "  ✓ 条件分岐が完全に排除\n\n";

echo str_repeat("─", 60) . "\n\n";


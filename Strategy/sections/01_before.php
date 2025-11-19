<?php

require_once __DIR__ . '/../PaymentStrategy.php';

// ============================================================
// 1. Before: 条件分岐による実装の問題点
// ============================================================
echo "【1】Before: 条件分岐による実装\n";
echo str_repeat("=", 60) . "\n\n";

$processorBefore = new PaymentProcessorBefore();

try {
    $processorBefore->processPayment('credit_card', 10000);
    $processorBefore->processPayment('paypal', 20000);
    $processorBefore->processPayment('bank_transfer', 30000);
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}

echo "問題点:\n";
echo "  ✗ 新しい決済方法の追加で既存コードを変更(開放閉鎖原則違反)\n";
echo "  ✗ 条件分岐が増えると可読性が低下\n";
echo "  ✗ 各決済方法を独立してテストできない\n";
echo "  ✗ すべての決済ロジックが1つのクラスに集中\n\n";

echo str_repeat("─", 60) . "\n\n";


<?php

require_once __DIR__ . '/../PaymentStrategy.php';

// ApplePayStrategyクラスが定義されていない場合はセクション3を読み込む
// メインファイルから呼び出す場合、セクション3は既に実行されているので
// require_onceにより再度実行されることはない
if (!class_exists('ApplePayStrategy')) {
    require_once __DIR__ . '/03_ocp_demo.php';
}

// ============================================================
// 6. 実践例: ユーザー選択による戦略の切り替え
// ============================================================
echo "【6】実践例: ユーザー入力による動的な戦略選択\n";
echo str_repeat("=", 60) . "\n\n";

/**
 * 戦略を選択するためのファクトリー(Strategy + Factory の組み合わせ)
 */
function selectPaymentStrategy(string $userChoice): PaymentStrategy
{
    return match ($userChoice) {
        'credit_card' => new CreditCardStrategy(),
        'paypal' => new PayPalStrategy(),
        'bank_transfer' => new BankTransferStrategy(),
        'bitcoin' => new BitcoinStrategy(),
        'apple_pay' => new ApplePayStrategy(),
        default => throw new InvalidArgumentException("未対応の決済方法: {$userChoice}")
    };
}

// ユーザーの選択をシミュレート
$userChoices = ['credit_card', 'paypal', 'bitcoin', 'apple_pay'];

$paymentContext4 = new PaymentContext();

foreach ($userChoices as $index => $choice) {
    echo "▼ ユーザーが選択した決済方法: {$choice}\n";
    $strategy = selectPaymentStrategy($choice);
    $paymentContext4->setStrategy($strategy);
    $paymentContext4->executePayment(($index + 1) * 10000);
}

echo str_repeat("─", 60) . "\n\n";


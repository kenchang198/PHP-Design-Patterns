<?php

require_once __DIR__ . '/../PaymentStrategy.php';

// ============================================================
// 3. 開放閉鎖原則のデモ: 新しい決済方法の追加
// ============================================================
echo "【3】開放閉鎖原則(OCP)のデモ: 新しい決済方法の追加\n";
echo str_repeat("=", 60) . "\n\n";

/**
 * 新しい決済戦略: Apple Pay
 *
 * 既存のコードを一切変更せず、新しいクラスを追加するだけで
 * 新しい決済方法を実装できる(開放閉鎖原則の実践)
 */
class ApplePayStrategy implements PaymentStrategy
{
    private string $deviceId;

    public function __construct(string $deviceId = 'iPhone-12-Pro')
    {
        $this->deviceId = $deviceId;
    }

    public function processPayment(float $amount): void
    {
        echo "=== Apple Pay決済 ===\n";
        echo "デバイス: {$this->deviceId}\n";
        echo "金額: ¥" . number_format($amount) . "\n";
        echo "Face IDで認証中...\n";
        echo "Apple Payサーバーと通信中...\n";
        echo "✓ 決済が完了しました!\n\n";
    }
}

echo "▼ 新しい決済方法: Apple Pay\n";
echo "※ 既存のPaymentContext、他の戦略クラスは一切変更していません\n\n";

$paymentContext = new PaymentContext();
$applePay = new ApplePayStrategy('iPhone-14-Pro');
$paymentContext->setStrategy($applePay);
$paymentContext->executePayment(75000);

echo "重要なポイント:\n";
echo "  ✓ PaymentContext クラスを変更する必要なし\n";
echo "  ✓ 既存の戦略クラス(CreditCard, PayPalなど)を変更する必要なし\n";
echo "  ✓ 新しいクラス(ApplePayStrategy)を追加するだけで機能拡張\n";
echo "  → これが開放閉鎖原則(OCP)の実践です\n\n";

echo str_repeat("─", 60) . "\n\n";


<?php

// 抽象的な決済処理のインターフェース
interface PaymentProcessor {
    public function processPayment($amount);
}

// 具体的な決済処理クラス：クレジットカード
class CreditCardProcessor implements PaymentProcessor {
    public function processPayment($amount) {
        echo "クレジットカードで{$amount}円の決済を処理しました。\n";
        return true;
    }
}

// 具体的な決済処理クラス：PayPal
class PayPalProcessor implements PaymentProcessor {
    public function processPayment($amount) {
        echo "PayPalで{$amount}円の決済を処理しました。\n";
        return true;
    }
}

// 具体的な決済処理クラス：銀行振込
class BankTransferProcessor implements PaymentProcessor {
    public function processPayment($amount) {
        echo "銀行振込で{$amount}円の決済を処理しました。\n";
        return true;
    }
}

// 抽象的なファクトリークラス
abstract class PaymentFactory {
    // ファクトリーメソッド
    abstract public function createProcessor(): PaymentProcessor;
    
    // テンプレートメソッド
    public function processPayment($amount) {
        $processor = $this->createProcessor();
        return $processor->processPayment($amount);
    }
}

// 具体的なファクトリークラス：クレジットカード
class CreditCardFactory extends PaymentFactory {
    public function createProcessor(): PaymentProcessor {
        return new CreditCardProcessor();
    }
}

// 具体的なファクトリークラス：PayPal
class PayPalFactory extends PaymentFactory {
    public function createProcessor(): PaymentProcessor {
        return new PayPalProcessor();
    }
}

// 具体的なファクトリークラス：銀行振込
class BankTransferFactory extends PaymentFactory {
    public function createProcessor(): PaymentProcessor {
        return new BankTransferProcessor();
    }
}

// 使用例
function processOrder($paymentType, $amount) {
    $factory = null;
    
    switch ($paymentType) {
        case 'credit_card':
            $factory = new CreditCardFactory();
            break;
        case 'paypal':
            $factory = new PayPalFactory();
            break;
        case 'bank_transfer':
            $factory = new BankTransferFactory();
            break;
        default:
            throw new Exception("サポートされていない決済方法: {$paymentType}");
    }
    
    $factory->processPayment($amount);
}

// テスト実行
try {
    processOrder('credit_card', 5000);
    processOrder('paypal', 3000);
    processOrder('bank_transfer', 10000);
    // エラーケース
    // processOrder('bitcoin', 2000);
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}

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
    public function pay($amount) {
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


// Factoryインスタンスを生成する関数
function createPaymentFactory($paymentType): PaymentFactory {
    switch ($paymentType) {
        case 'credit_card':
            return new CreditCardFactory();
        case 'paypal':
            return new PayPalFactory();
        case 'bank_transfer':
            return new BankTransferFactory();
        default:
            throw new Exception("サポートされていない決済方法: {$paymentType}");
    }
}

function executePayment(PaymentFactory $factory, $amount) {
    $factory->pay($amount);
}

try {
    $factory1 = createPaymentFactory('credit_card');
    executePayment($factory1, 5000);
    
    $factory2 = createPaymentFactory('paypal');
    executePayment($factory2, 3000);
    
    $factory3 = createPaymentFactory('bank_transfer');
    executePayment($factory3, 10000);
    
    // エラーケース
    // $factory4 = createPaymentFactory('bitcoin');
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}
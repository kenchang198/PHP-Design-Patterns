<?php

// 抽象的な決済処理のインターフェース
interface PaymentProcessor {
    public function processPayment($amount);
}

// 具体的な決済処理クラス：クレジットカード
class CreditCardProcessor implements PaymentProcessor {
    private $apiKey;
    
    public function __construct($apiKey = 'default_key') {
        $this->apiKey = $apiKey;
    }
    
    public function processPayment($amount) {
        echo "クレジットカードで{$amount}円の決済を処理しました。APIキー: {$this->apiKey}\n";
        return true;
    }
}

// 具体的な決済処理クラス：PayPal
class PayPalProcessor implements PaymentProcessor {
    private $clientId;
    private $secret;
    
    public function __construct($clientId = 'default_client_id', $secret = 'default_secret') {
        $this->clientId = $clientId;
        $this->secret = $secret;
    }
    
    public function processPayment($amount) {
        echo "PayPalで{$amount}円の決済を処理しました。クライアントID: {$this->clientId}\n";
        return true;
    }
}

// 具体的な決済処理クラス：銀行振込
class BankTransferProcessor implements PaymentProcessor {
    private $bankCode;
    private $accountNumber;
    
    public function __construct($bankCode = 'default_bank', $accountNumber = 'default_account') {
        $this->bankCode = $bankCode;
        $this->accountNumber = $accountNumber;
    }
    
    public function processPayment($amount) {
        echo "銀行振込で{$amount}円の決済を処理しました。銀行コード: {$this->bankCode}\n";
        return true;
    }
}

// 新しい決済方法：Bitcoin
class BitcoinProcessor implements PaymentProcessor {
    private $walletAddress;
    
    public function __construct($walletAddress = 'default_wallet') {
        $this->walletAddress = $walletAddress;
    }
    
    public function processPayment($amount) {
        echo "Bitcoinで{$amount}円の決済を処理しました。ウォレットアドレス: {$this->walletAddress}\n";
        return true;
    }
}

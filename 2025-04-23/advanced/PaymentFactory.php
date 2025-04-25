<?php

require_once 'PaymentProcessor.php';

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
    private $apiKey;
    
    public function __construct($apiKey = 'default_key') {
        $this->apiKey = $apiKey;
    }
    
    public function createProcessor(): PaymentProcessor {
        return new CreditCardProcessor($this->apiKey);
    }
}

// 具体的なファクトリークラス：PayPal
class PayPalFactory extends PaymentFactory {
    private $clientId;
    private $secret;
    
    public function __construct($clientId = 'default_client_id', $secret = 'default_secret') {
        $this->clientId = $clientId;
        $this->secret = $secret;
    }
    
    public function createProcessor(): PaymentProcessor {
        return new PayPalProcessor($this->clientId, $this->secret);
    }
}

// 具体的なファクトリークラス：銀行振込
class BankTransferFactory extends PaymentFactory {
    private $bankCode;
    private $accountNumber;
    
    public function __construct($bankCode = 'default_bank', $accountNumber = 'default_account') {
        $this->bankCode = $bankCode;
        $this->accountNumber = $accountNumber;
    }
    
    public function createProcessor(): PaymentProcessor {
        return new BankTransferProcessor($this->bankCode, $this->accountNumber);
    }
}

// 具体的なファクトリークラス：Bitcoin
class BitcoinFactory extends PaymentFactory {
    private $walletAddress;
    
    public function __construct($walletAddress = 'default_wallet') {
        $this->walletAddress = $walletAddress;
    }
    
    public function createProcessor(): PaymentProcessor {
        return new BitcoinProcessor($this->walletAddress);
    }
}

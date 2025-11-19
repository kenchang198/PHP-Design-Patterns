<?php

/**
 * Strategy パターン実装
 *
 * 決済処理のアルゴリズムをカプセル化し、実行時に切り替え可能にする。
 * これにより、条件分岐を削減し、開放閉鎖原則を実現する。
 */

/**
 * PaymentStrategy インターフェース
 *
 * すべての決済戦略が実装すべき共通のインターフェースを定義。
 * これにより、クライアント(Context)は具体的な戦略の実装詳細を知る必要がない。
 */
interface PaymentStrategy
{
    /**
     * 決済処理を実行
     *
     * @param float $amount 決済金額
     * @return void
     */
    public function processPayment(float $amount): void;
}

/**
 * クレジットカード決済戦略
 *
 * クレジットカードによる決済処理を実装。
 * 実際のアプリケーションでは、カード番号検証、セキュリティコード確認、
 * 決済ゲートウェイとの通信などを行う。
 */
class CreditCardStrategy implements PaymentStrategy
{
    private string $cardNumber;
    private string $cvv;

    /**
     * @param string $cardNumber カード番号
     * @param string $cvv セキュリティコード
     */
    public function __construct(string $cardNumber = '****-****-****-1234', string $cvv = '***')
    {
        $this->cardNumber = $cardNumber;
        $this->cvv = $cvv;
    }

    public function processPayment(float $amount): void
    {
        echo "=== クレジットカード決済 ===\n";
        echo "カード番号: {$this->cardNumber}\n";
        echo "金額: ¥" . number_format($amount) . "\n";
        echo "カード情報を検証中...\n";
        echo "決済ゲートウェイと通信中...\n";
        echo "✓ 決済が完了しました!\n\n";
    }
}

/**
 * PayPal決済戦略
 *
 * PayPalによる決済処理を実装。
 * 実際のアプリケーションでは、PayPal APIとの通信、
 * OAuth認証などを行う。
 */
class PayPalStrategy implements PaymentStrategy
{
    private string $email;

    /**
     * @param string $email PayPalアカウントのメールアドレス
     */
    public function __construct(string $email = 'user@example.com')
    {
        $this->email = $email;
    }

    public function processPayment(float $amount): void
    {
        echo "=== PayPal決済 ===\n";
        echo "アカウント: {$this->email}\n";
        echo "金額: ¥" . number_format($amount) . "\n";
        echo "PayPalにリダイレクト中...\n";
        echo "アカウント認証中...\n";
        echo "✓ 決済が完了しました!\n\n";
    }
}

/**
 * 銀行振込戦略
 *
 * 銀行振込による決済処理を実装。
 * 実際のアプリケーションでは、振込先口座情報の提示、
 * 入金確認などを行う。
 */
class BankTransferStrategy implements PaymentStrategy
{
    private string $bankCode;
    private string $accountNumber;

    /**
     * @param string $bankCode 銀行コード
     * @param string $accountNumber 口座番号
     */
    public function __construct(string $bankCode = '0001', string $accountNumber = '1234567')
    {
        $this->bankCode = $bankCode;
        $this->accountNumber = $accountNumber;
    }

    public function processPayment(float $amount): void
    {
        echo "=== 銀行振込 ===\n";
        echo "振込先銀行コード: {$this->bankCode}\n";
        echo "口座番号: {$this->accountNumber}\n";
        echo "金額: ¥" . number_format($amount) . "\n";
        echo "振込先口座を確認中...\n";
        echo "振込手数料を計算中...\n";
        echo "✓ 振込情報を発行しました。入金をお待ちしています。\n\n";
    }
}

/**
 * Bitcoin決済戦略
 *
 * Bitcoinによる決済処理を実装。
 * 実際のアプリケーションでは、ウォレットアドレス生成、
 * ブロックチェーンとの通信などを行う。
 */
class BitcoinStrategy implements PaymentStrategy
{
    private string $walletAddress;

    /**
     * @param string $walletAddress Bitcoinウォレットアドレス
     */
    public function __construct(string $walletAddress = '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa')
    {
        $this->walletAddress = $walletAddress;
    }

    public function processPayment(float $amount): void
    {
        echo "=== Bitcoin決済 ===\n";
        echo "ウォレットアドレス: {$this->walletAddress}\n";
        echo "金額: ¥" . number_format($amount) . "\n";
        echo "現在のBTC/JPYレートを取得中...\n";
        echo "ウォレットアドレスを検証中...\n";
        echo "ブロックチェーンにトランザクションを送信中...\n";
        echo "✓ 決済が完了しました!\n\n";
    }
}

/**
 * PaymentContext クラス
 *
 * 決済戦略を管理し、実際の決済処理を戦略に委譲するコンテキストクラス。
 * このクラスは具体的な決済方法の実装詳細を知らず、
 * PaymentStrategyインターフェースを通じて通信する。
 *
 * 責務:
 * 1. 戦略オブジェクトの保持
 * 2. 実行時の戦略切り替え
 * 3. 処理の委譲
 */
class PaymentContext
{
    private ?PaymentStrategy $strategy = null;

    /**
     * コンストラクタ注入による戦略の設定
     *
     * @param PaymentStrategy|null $strategy 使用する決済戦略(オプション)
     */
    public function __construct(?PaymentStrategy $strategy = null)
    {
        $this->strategy = $strategy;
    }

    /**
     * 実行時に戦略を変更
     *
     * このメソッドにより、同じContextインスタンスで
     * 異なる決済方法を切り替えることができる。
     *
     * @param PaymentStrategy $strategy 新しい決済戦略
     * @return void
     */
    public function setStrategy(PaymentStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * 決済処理を実行
     *
     * 実際の処理は設定されている戦略に委譲される。
     * Contextは戦略の実装詳細を知る必要がない。
     *
     * @param float $amount 決済金額
     * @return void
     * @throws Exception 戦略が設定されていない場合
     */
    public function executePayment(float $amount): void
    {
        if ($this->strategy === null) {
            throw new Exception("決済戦略が設定されていません。setStrategy()で戦略を設定してください。");
        }

        $this->strategy->processPayment($amount);
    }

    /**
     * 現在の戦略を取得
     *
     * @return PaymentStrategy|null 現在設定されている戦略
     */
    public function getStrategy(): ?PaymentStrategy
    {
        return $this->strategy;
    }
}

/**
 * 【参考】Before: 条件分岐による実装
 *
 * Strategy パターンを使わない場合の典型的な実装例。
 * 新しい決済方法を追加するたびに、このクラスを修正する必要がある。
 */
class PaymentProcessorBefore
{
    /**
     * 決済処理(条件分岐による実装)
     *
     * 問題点:
     * - 開放閉鎖原則違反(新しい決済方法の追加で既存コードを変更)
     * - 単一責任の原則違反(すべての決済方法を1つのクラスが知っている)
     * - 可読性の低下(条件分岐が増えるほど複雑になる)
     * - テストが困難(各決済方法を独立してテストできない)
     *
     * @param string $type 決済タイプ
     * @param float $amount 決済金額
     * @return void
     * @throws Exception 未対応の決済方法の場合
     */
    public function processPayment(string $type, float $amount): void
    {
        if ($type === 'credit_card') {
            echo "クレジットカードで¥" . number_format($amount) . "を決済中...\n";
            echo "カード番号を検証中...\n";
            echo "決済完了!\n\n";
        } elseif ($type === 'paypal') {
            echo "PayPalで¥" . number_format($amount) . "を決済中...\n";
            echo "PayPalアカウントに接続中...\n";
            echo "決済完了!\n\n";
        } elseif ($type === 'bank_transfer') {
            echo "銀行振込で¥" . number_format($amount) . "を決済中...\n";
            echo "振込先口座を確認中...\n";
            echo "決済完了!\n\n";
        } elseif ($type === 'bitcoin') {
            echo "Bitcoinで¥" . number_format($amount) . "を決済中...\n";
            echo "ウォレットアドレスを検証中...\n";
            echo "決済完了!\n\n";
        } else {
            throw new Exception("未対応の決済方法: {$type}");
        }

        // 決済方法が増えるたびに、ここにelseifを追加する必要がある...
        // これは開放閉鎖原則に違反している
    }
}

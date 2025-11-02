<?php

namespace App\Patterns\AbstractFactory\Advanced;

require_once 'NotificationFactory.php';

/**
 * SMSメッセージの具体的実装
 */
class SmsMessage implements Message
{
    private string $recipient = '';
    private string $subject = '';
    private string $content = '';
    private array $attachments = []; // SMSでは実際には使用しないが、インターフェース互換性のため実装
    
    /**
     * 受信者を設定
     *
     * @param string $recipient 受信者電話番号
     * @return void
     */
    public function setRecipient(string $recipient): void
    {
        // 電話番号のバリデーション
        // E.164形式（国際規格）をチェック： +[国コード][電話番号]
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $recipient) && !preg_match('/^[0-9]{10,11}$/', $recipient)) {
            throw new \InvalidArgumentException('無効な電話番号形式です: ' . $recipient);
        }
        
        $this->recipient = $recipient;
    }
    
    /**
     * 件名を設定
     * SMSでは通常件名は使用しないが、インターフェース互換性のため実装
     *
     * @param string $subject 件名
     * @return void
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }
    
    /**
     * 本文を設定
     *
     * @param string $content 本文
     * @return void
     */
    public function setContent(string $content): void
    {
        // SMSメッセージの長さ制限（160文字）を考慮
        if (mb_strlen($content) > 160) {
            // 警告ログを出力するか、例外をスローする代わりに切り詰める
            $content = mb_substr($content, 0, 157) . '...';
        }
        
        $this->content = $content;
    }
    
    /**
     * 添付ファイルを追加
     * SMSでは通常添付ファイルはサポートされないが、インターフェース互換性のため実装
     *
     * @param string $filePath ファイルパス
     * @param string $mimeType MIMEタイプ
     * @return void
     */
    public function addAttachment(string $filePath, string $mimeType): void
    {
        // SMSでは添付ファイルをサポートしていないため、警告ログを出力
        // 実際のアプリケーションでは適切なログシステムを使用
        error_log('警告: SMSメッセージでは添付ファイルはサポートされていません - 無視されます: ' . $filePath);
    }
    
    /**
     * フォーマット済みのメッセージを取得
     *
     * @return string フォーマット済みメッセージ
     */
    public function getFormattedMessage(): string
    {
        // SMSは単純なテキストメッセージ
        // 件名がある場合は本文の前に付加する
        $message = '';
        if (!empty($this->subject)) {
            $message = "[{$this->subject}] ";
        }
        
        $message .= $this->content;
        
        return $message;
    }
}

/**
 * SMS送信者の具体的実装
 */
class SmsSender implements Sender
{
    private array $config = [];
    private string $status = 'initialized';
    private array $logs = [];
    
    /**
     * 構成情報を設定
     *
     * @param array $config 構成情報（SMSプロバイダーのAPI設定など）
     * @return void
     */
    public function configure(array $config): void
    {
        // 必要な設定キーをバリデーション
        $requiredKeys = ['api_key', 'api_secret', 'sender_id'];
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key])) {
                throw new \InvalidArgumentException("必須の設定項目が不足しています: {$key}");
            }
        }
        
        $this->config = $config;
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'SMS API設定が構成されました',
            'detail' => 'provider: ' . ($config['provider'] ?? 'default') . ', sender_id: ' . $config['sender_id']
        ];
    }
    
    /**
     * SMSを送信
     *
     * @param Message $message 送信するメッセージ
     * @return bool 送信成功したかどうか
     */
    public function send(Message $message): bool
    {
        if (empty($this->config)) {
            $this->status = 'error';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信エラー: API設定が構成されていません',
                'detail' => 'configure()メソッドを先に呼び出してください'
            ];
            return false;
        }
        
        if (!$message instanceof SmsMessage) {
            $this->status = 'error';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信エラー: 不正なメッセージタイプ',
                'detail' => 'SmsMessage型が必要です'
            ];
            return false;
        }
        
        // 実際のアプリケーションではここでSMS APIリクエストを送信
        // この例ではシミュレーションとして成功したと仮定
        
        // SMS APIリクエストをログに記録
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'SMS APIにリクエストを送信しました',
            'detail' => 'API endpoint: ' . ($this->config['api_endpoint'] ?? 'https://api.sms-provider.com/v1/send')
        ];
        
        // SMS送信をログに記録
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'SMSを送信中',
            'detail' => $message->getFormattedMessage()
        ];
        
        // 95%の確率で成功、5%の確率で失敗するシミュレーション
        $success = (rand(1, 100) > 5);
        
        if ($success) {
            $this->status = 'delivered';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信成功',
                'detail' => '送信ID: ' . uniqid('sms_')
            ];
        } else {
            $this->status = 'failed';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信失敗',
                'detail' => 'SMS APIからのエラー: 通信エラーまたは無効な電話番号'
            ];
        }
        
        return $success;
    }
    
    /**
     * 送信ステータスを取得
     *
     * @return string 送信ステータス
     */
    public function getDeliveryStatus(): string
    {
        return $this->status;
    }
    
    /**
     * 送信ログを取得
     *
     * @return array 送信ログ
     */
    public function getDeliveryLogs(): array
    {
        return $this->logs;
    }
}

/**
 * SMS通知ファクトリの具体的実装
 */
class SmsNotificationFactory implements NotificationFactory
{
    /**
     * SMSメッセージを生成
     *
     * @return Message
     */
    public function createMessage(): Message
    {
        return new SmsMessage();
    }
    
    /**
     * SMS送信者を生成
     *
     * @return Sender
     */
    public function createSender(): Sender
    {
        return new SmsSender();
    }
}

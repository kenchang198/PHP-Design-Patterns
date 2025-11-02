<?php

namespace App\Patterns\AbstractFactory\Advanced;

require_once 'NotificationFactory.php';

/**
 * メールメッセージの具体的実装
 */
class EmailMessage implements Message
{
    private string $recipient = '';
    private string $subject = '';
    private string $content = '';
    private array $attachments = [];
    private array $headers = [];
    
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // デフォルトヘッダーの設定
        $this->headers = [
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/html; charset=UTF-8',
            'From' => 'system@example.com',
            'Reply-To' => 'noreply@example.com'
        ];
    }
    
    /**
     * 受信者を設定
     *
     * @param string $recipient 受信者メールアドレス
     * @return void
     */
    public function setRecipient(string $recipient): void
    {
        // メールアドレスのバリデーションを実装するとより実践的
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('無効なメールアドレスです: ' . $recipient);
        }
        
        $this->recipient = $recipient;
    }
    
    /**
     * 件名を設定
     *
     * @param string $subject 件名
     * @return void
     */
    public function setSubject(string $subject): void
    {
        // 件名は特殊文字が含まれる可能性があるため、適切にエンコード
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
        // HTML形式の場合、HTMLエスケープを考慮
        $this->content = $content;
    }
    
    /**
     * 添付ファイルを追加
     *
     * @param string $filePath ファイルパス
     * @param string $mimeType MIMEタイプ
     * @return void
     */
    public function addAttachment(string $filePath, string $mimeType): void
    {
        // ファイルの存在チェック
        if (!file_exists($filePath)) {
            throw new \RuntimeException('添付ファイルが見つかりません: ' . $filePath);
        }
        
        $this->attachments[] = [
            'path' => $filePath,
            'mime' => $mimeType,
            'name' => basename($filePath)
        ];
    }
    
    /**
     * メールヘッダーを設定
     *
     * @param string $name ヘッダー名
     * @param string $value ヘッダー値
     * @return void
     */
    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }
    
    /**
     * フォーマット済みのメッセージを取得
     *
     * @return string フォーマット済みメッセージ
     */
    public function getFormattedMessage(): string
    {
        // 実際のアプリケーションではMIMEエンコードやマルチパート形式の処理が必要
        // ここでは簡略化したフォーマットを返す
        $formatted = "To: {$this->recipient}\n";
        $formatted .= "Subject: {$this->subject}\n";
        
        foreach ($this->headers as $name => $value) {
            $formatted .= "{$name}: {$value}\n";
        }
        
        $formatted .= "\n{$this->content}\n";
        
        if (!empty($this->attachments)) {
            $formatted .= "\n添付ファイル:\n";
            foreach ($this->attachments as $attachment) {
                $formatted .= "- {$attachment['name']} ({$attachment['mime']})\n";
            }
        }
        
        return $formatted;
    }
}

/**
 * メール送信者の具体的実装
 */
class EmailSender implements Sender
{
    private array $config = [];
    private string $status = 'initialized';
    private array $logs = [];
    
    /**
     * 構成情報を設定
     *
     * @param array $config 構成情報（SMTPサーバー設定など）
     * @return void
     */
    public function configure(array $config): void
    {
        // 必要な設定キーをバリデーション
        $requiredKeys = ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_password'];
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key])) {
                throw new \InvalidArgumentException("必須の設定項目が不足しています: {$key}");
            }
        }
        
        $this->config = $config;
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'SMTP設定が構成されました',
            'detail' => 'host: ' . $config['smtp_host'] . ', port: ' . $config['smtp_port']
        ];
    }
    
    /**
     * メールを送信
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
                'message' => '送信エラー: SMTPが設定されていません',
                'detail' => 'configure()メソッドを先に呼び出してください'
            ];
            return false;
        }
        
        if (!$message instanceof EmailMessage) {
            $this->status = 'error';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信エラー: 不正なメッセージタイプ',
                'detail' => 'EmailMessage型が必要です'
            ];
            return false;
        }
        
        // 実際のアプリケーションではここでSMTP接続とメール送信を行う
        // この例ではシミュレーションとして成功したと仮定
        
        // SMTPサーバーへの接続をログに記録
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'SMTPサーバーに接続しました',
            'detail' => $this->config['smtp_host'] . ':' . $this->config['smtp_port']
        ];
        
        // メール送信をログに記録
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'メールを送信しました',
            'detail' => $message->getFormattedMessage()
        ];
        
        // 99%の確率で成功、1%の確率で失敗するシミュレーション
        $success = (rand(1, 100) > 1);
        
        if ($success) {
            $this->status = 'delivered';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信成功',
                'detail' => '送信ID: ' . uniqid('mail_')
            ];
        } else {
            $this->status = 'failed';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信失敗',
                'detail' => 'SMTPサーバーからのエラー: 一時的なエラーが発生しました'
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
 * メール通知ファクトリの具体的実装
 */
class EmailNotificationFactory implements NotificationFactory
{
    /**
     * メールメッセージを生成
     *
     * @return Message
     */
    public function createMessage(): Message
    {
        return new EmailMessage();
    }
    
    /**
     * メール送信者を生成
     *
     * @return Sender
     */
    public function createSender(): Sender
    {
        return new EmailSender();
    }
}

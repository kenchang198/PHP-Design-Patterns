<?php

namespace App\Patterns\AbstractFactory\Advanced;

require_once 'NotificationFactory.php';

/**
 * プッシュ通知メッセージの具体的実装
 */
class PushMessage implements Message
{
    private string $recipient = ''; // デバイストークンまたはユーザーID
    private string $subject = '';
    private string $content = '';
    private array $attachments = [];
    private array $payload = []; // プッシュ通知の追加データ
    
    /**
     * 受信者を設定
     *
     * @param string $recipient デバイストークンまたはユーザーID
     * @return void
     */
    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }
    
    /**
     * 件名を設定
     *
     * @param string $subject 件名（プッシュ通知のタイトル）
     * @return void
     */
    public function setSubject(string $subject): void
    {
        // プッシュ通知のタイトルは短くする必要がある
        if (mb_strlen($subject) > 50) {
            $subject = mb_substr($subject, 0, 47) . '...';
        }
        
        $this->subject = $subject;
    }
    
    /**
     * 本文を設定
     *
     * @param string $content 本文（プッシュ通知の本文）
     * @return void
     */
    public function setContent(string $content): void
    {
        // プッシュ通知の本文も長さ制限がある
        if (mb_strlen($content) > 200) {
            $content = mb_substr($content, 0, 197) . '...';
        }
        
        $this->content = $content;
    }
    
    /**
     * 添付ファイルを追加
     * プッシュ通知ではファイルではなく画像URLなどを扱う
     *
     * @param string $filePath 画像URL
     * @param string $mimeType MIMEタイプ
     * @return void
     */
    public function addAttachment(string $filePath, string $mimeType): void
    {
        // プッシュ通知で画像を使用する場合はURL形式が一般的
        if (!filter_var($filePath, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('無効なURL形式です: ' . $filePath);
        }
        
        $this->attachments[] = [
            'url' => $filePath,
            'type' => $mimeType
        ];
    }
    
    /**
     * プッシュ通知の追加データを設定
     *
     * @param array $data 追加データ
     * @return void
     */
    public function setPayload(array $data): void
    {
        $this->payload = $data;
    }
    
    /**
     * フォーマット済みのメッセージを取得
     *
     * @return string フォーマット済みメッセージ
     */
    public function getFormattedMessage(): string
    {
        // プッシュ通知のJSONペイロード形式
        $formatted = json_encode([
            'to' => $this->recipient,
            'notification' => [
                'title' => $this->subject,
                'body' => $this->content,
                'image' => isset($this->attachments[0]) ? $this->attachments[0]['url'] : null
            ],
            'data' => $this->payload
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        return $formatted;
    }
}

/**
 * プッシュ通知送信者の具体的実装
 */
class PushSender implements Sender
{
    private array $config = [];
    private string $status = 'initialized';
    private array $logs = [];
    
    /**
     * 構成情報を設定
     *
     * @param array $config 構成情報（FCM/APNSキーなど）
     * @return void
     */
    public function configure(array $config): void
    {
        // 必要な設定キーをバリデーション
        $requiredKeys = ['service_type', 'api_key'];
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key])) {
                throw new \InvalidArgumentException("必須の設定項目が不足しています: {$key}");
            }
        }
        
        // サービスタイプをバリデーション
        if (!in_array($config['service_type'], ['fcm', 'apns'])) {
            throw new \InvalidArgumentException("サポートされていないサービスタイプです: {$config['service_type']}");
        }
        
        $this->config = $config;
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'プッシュ通知設定が構成されました',
            'detail' => 'service: ' . $config['service_type']
        ];
    }
    
    /**
     * プッシュ通知を送信
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
        
        if (!$message instanceof PushMessage) {
            $this->status = 'error';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信エラー: 不正なメッセージタイプ',
                'detail' => 'PushMessage型が必要です'
            ];
            return false;
        }
        
        // 実際のアプリケーションではここでFCMまたはAPNS APIリクエストを送信
        // この例ではシミュレーションとして成功したと仮定
        
        // API URLを決定
        $apiUrl = $this->config['service_type'] === 'fcm' 
            ? 'https://fcm.googleapis.com/fcm/send'
            : 'https://api.push.apple.com/3/device';
        
        // API リクエストをログに記録
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'プッシュ通知 APIにリクエストを送信しました',
            'detail' => 'API endpoint: ' . $apiUrl
        ];
        
        // 送信内容をログに記録
        $this->logs[] = [
            'time' => date('Y-m-d H:i:s'),
            'message' => 'プッシュ通知を送信中',
            'detail' => $message->getFormattedMessage()
        ];
        
        // 90%の確率で成功、10%の確率で失敗するシミュレーション
        $success = (rand(1, 100) > 10);
        
        if ($success) {
            $this->status = 'delivered';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信成功',
                'detail' => '送信ID: ' . uniqid('push_')
            ];
        } else {
            $this->status = 'failed';
            $this->logs[] = [
                'time' => date('Y-m-d H:i:s'),
                'message' => '送信失敗',
                'detail' => 'エラー: トークンが無効か期限切れです'
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
 * プッシュ通知ファクトリの具体的実装
 */
class PushNotificationFactory implements NotificationFactory
{
    /**
     * プッシュ通知メッセージを生成
     *
     * @return Message
     */
    public function createMessage(): Message
    {
        return new PushMessage();
    }
    
    /**
     * プッシュ通知送信者を生成
     *
     * @return Sender
     */
    public function createSender(): Sender
    {
        return new PushSender();
    }
}

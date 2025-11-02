<?php

namespace App\Patterns\AbstractFactory\Advanced;

require_once 'EmailNotificationFactory.php';
require_once 'SmsNotificationFactory.php';
require_once 'PushNotificationFactory.php';

/**
 * 通知サービス
 * どの通知チャネルを使うかは実行時に指定可能
 */
class NotificationService
{
    private NotificationFactory $factory;
    private Message $message;
    private Sender $sender;
    private array $config = [];
    
    /**
     * コンストラクタ
     * 
     * @param NotificationFactory $factory 使用する通知ファクトリ
     * @param array $config 通知チャネルの設定
     */
    public function __construct(NotificationFactory $factory, array $config = [])
    {
        $this->factory = $factory;
        $this->message = $factory->createMessage();
        $this->sender = $factory->createSender();
        
        if (!empty($config)) {
            $this->configure($config);
        }
    }
    
    /**
     * 通知チャネルを変更
     * 
     * @param NotificationFactory $factory 新しい通知ファクトリ
     * @param array $config 新しい通知チャネルの設定
     * @return void
     */
    public function changeChannel(NotificationFactory $factory, array $config = []): void
    {
        $this->factory = $factory;
        $this->message = $factory->createMessage();
        $this->sender = $factory->createSender();
        
        if (!empty($config)) {
            $this->configure($config);
        }
    }
    
    /**
     * 送信者の設定を構成
     * 
     * @param array $config 設定情報
     * @return void
     */
    public function configure(array $config): void
    {
        $this->config = $config;
        $this->sender->configure($config);
    }
    
    /**
     * 通知を送信
     * 
     * @param string $recipient 受信者
     * @param string $subject 件名
     * @param string $content 本文
     * @param array $attachments 添付ファイル（オプション）
     * @param array $options その他のオプション（オプション）
     * @return bool 送信成功したかどうか
     */
    public function notify(
        string $recipient,
        string $subject,
        string $content,
        array $attachments = [],
        array $options = []
    ): bool {
        // メッセージを構成
        $this->message->setRecipient($recipient);
        $this->message->setSubject($subject);
        $this->message->setContent($content);
        
        // 添付ファイルを追加
        foreach ($attachments as $attachment) {
            if (isset($attachment['path']) && isset($attachment['type'])) {
                $this->message->addAttachment($attachment['path'], $attachment['type']);
            }
        }
        
        // プッシュ通知の場合はペイロードを設定
        if ($this->message instanceof PushMessage && isset($options['payload'])) {
            $this->message->setPayload($options['payload']);
        }
        
        // メッセージを送信
        return $this->sender->send($this->message);
    }
    
    /**
     * 送信ステータスを取得
     * 
     * @return string 送信ステータス
     */
    public function getStatus(): string
    {
        return $this->sender->getDeliveryStatus();
    }
    
    /**
     * 送信ログを取得
     * 
     * @return array 送信ログ
     */
    public function getLogs(): array
    {
        return $this->sender->getDeliveryLogs();
    }
}

/**
 * 複数の通知チャネルを組み合わせる複合通知サービス
 */
class CompositeNotificationService
{
    private array $services = [];
    
    /**
     * 通知サービスを追加
     * 
     * @param string $name サービス名
     * @param NotificationService $service 通知サービス
     * @return void
     */
    public function addService(string $name, NotificationService $service): void
    {
        $this->services[$name] = $service;
    }
    
    /**
     * 通知サービスを取得
     * 
     * @param string $name サービス名
     * @return NotificationService|null 通知サービス
     */
    public function getService(string $name): ?NotificationService
    {
        return $this->services[$name] ?? null;
    }
    
    /**
     * すべてのチャネルで通知を送信
     * 
     * @param string $recipient 受信者
     * @param string $subject 件名
     * @param string $content 本文
     * @param array $attachments 添付ファイル（オプション）
     * @param array $options その他のオプション（オプション）
     * @return array 送信結果（サービス名 => 成功/失敗）
     */
    public function notifyAll(
        string $recipient,
        string $subject,
        string $content,
        array $attachments = [],
        array $options = []
    ): array {
        $results = [];
        
        foreach ($this->services as $name => $service) {
            $results[$name] = $service->notify($recipient, $subject, $content, $attachments, $options);
        }
        
        return $results;
    }
    
    /**
     * 特定のチャネルで通知を送信
     * 
     * @param array $channels 送信するチャネル名の配列
     * @param string $recipient 受信者
     * @param string $subject 件名
     * @param string $content 本文
     * @param array $attachments 添付ファイル（オプション）
     * @param array $options その他のオプション（オプション）
     * @return array 送信結果（サービス名 => 成功/失敗）
     */
    public function notifySelected(
        array $channels,
        string $recipient,
        string $subject,
        string $content,
        array $attachments = [],
        array $options = []
    ): array {
        $results = [];
        
        foreach ($channels as $channel) {
            if (isset($this->services[$channel])) {
                $results[$channel] = $this->services[$channel]->notify(
                    $recipient,
                    $subject,
                    $content,
                    $attachments,
                    $options
                );
            } else {
                $results[$channel] = false;
            }
        }
        
        return $results;
    }
    
    /**
     * すべてのサービスの送信ステータスを取得
     * 
     * @return array 送信ステータス（サービス名 => ステータス）
     */
    public function getAllStatus(): array
    {
        $statuses = [];
        
        foreach ($this->services as $name => $service) {
            $statuses[$name] = $service->getStatus();
        }
        
        return $statuses;
    }
}

// 使用例

// E メール通知の設定と使用
echo "===== E メール通知のテスト =====\n";
$emailConfig = [
    'smtp_host' => 'smtp.example.com',
    'smtp_port' => 587,
    'smtp_username' => 'user@example.com',
    'smtp_password' => 'password123',
    'smtp_encryption' => 'tls'
];

$emailService = new NotificationService(new EmailNotificationFactory(), $emailConfig);
$emailResult = $emailService->notify(
    'recipient@example.com',
    'お支払い完了のお知らせ',
    '<p>いつも当サービスをご利用いただきありがとうございます。<br>ご注文番号 #12345 のお支払いが正常に完了しました。</p>',
    [
        [
            'path' => '/path/to/invoice.pdf', 
            'type' => 'application/pdf'
        ]
    ]
);

echo "E メール送信結果: " . ($emailResult ? '成功' : '失敗') . "\n";
echo "E メール送信ステータス: " . $emailService->getStatus() . "\n";
echo "E メール送信ログ:\n";
foreach ($emailService->getLogs() as $log) {
    echo "  " . $log['time'] . " - " . $log['message'] . "\n";
}

// SMS 通知の設定と使用
echo "\n===== SMS 通知のテスト =====\n";
$smsConfig = [
    'provider' => 'twilio',
    'api_key' => 'your_api_key',
    'api_secret' => 'your_api_secret',
    'sender_id' => 'MYCOMPANY'
];

$smsService = new NotificationService(new SmsNotificationFactory(), $smsConfig);
$smsResult = $smsService->notify(
    '+81901234567',
    'お知らせ', // SMSでは件名は使用されないことが多い
    'いつも当サービスをご利用いただきありがとうございます。ご注文番号 #12345 のお支払いが正常に完了しました。'
);

echo "SMS 送信結果: " . ($smsResult ? '成功' : '失敗') . "\n";
echo "SMS 送信ステータス: " . $smsService->getStatus() . "\n";
echo "SMS 送信ログ:\n";
foreach ($smsService->getLogs() as $log) {
    echo "  " . $log['time'] . " - " . $log['message'] . "\n";
}

// プッシュ通知の設定と使用
echo "\n===== プッシュ通知のテスト =====\n";
$pushConfig = [
    'service_type' => 'fcm',
    'api_key' => 'your_fcm_server_key'
];

$pushService = new NotificationService(new PushNotificationFactory(), $pushConfig);
$pushResult = $pushService->notify(
    'device_token_or_user_id',
    'お支払い完了',
    'ご注文番号 #12345 のお支払いが正常に完了しました。',
    [
        [
            'path' => 'https://example.com/images/success.png', 
            'type' => 'image/png'
        ]
    ],
    [
        'payload' => [
            'order_id' => 12345,
            'amount' => 5000,
            'deep_link' => 'myapp://orders/12345'
        ]
    ]
);

echo "プッシュ通知送信結果: " . ($pushResult ? '成功' : '失敗') . "\n";
echo "プッシュ通知送信ステータス: " . $pushService->getStatus() . "\n";
echo "プッシュ通知送信ログ:\n";
foreach ($pushService->getLogs() as $log) {
    echo "  " . $log['time'] . " - " . $log['message'] . "\n";
}

// 複合通知サービスのテスト
echo "\n===== 複合通知サービスのテスト =====\n";
$compositeService = new CompositeNotificationService();
$compositeService->addService('email', $emailService);
$compositeService->addService('sms', $smsService);
$compositeService->addService('push', $pushService);

// すべてのチャネルで通知
$allResults = $compositeService->notifyAll(
    'user@example.com', // 実際には各チャネルに対応した受信者形式が必要
    '重要なお知らせ',
    'システムメンテナンスのお知らせ: 本日23時から翌朝5時までメンテナンスのためサービスをご利用いただけません。'
);

echo "複合通知結果:\n";
foreach ($allResults as $channel => $result) {
    echo "  " . $channel . ": " . ($result ? '成功' : '失敗') . "\n";
}

// 重要度の高い通知は複数チャネルで、それ以外は単一チャネルで送信するなど
// 状況に応じて適切なチャネルを選択できる
$selectedResults = $compositeService->notifySelected(
    ['email', 'sms'], // 重要な通知なのでメールとSMSで送信
    'user@example.com', // 例示的なもの
    '緊急メンテナンスのお知らせ',
    '緊急システムメンテナンスを実施します。詳細はメールをご確認ください。'
);

echo "\n選択的通知結果:\n";
foreach ($selectedResults as $channel => $result) {
    echo "  " . $channel . ": " . ($result ? '成功' : '失敗') . "\n";
}

// チャネルを動的に切り替え
echo "\n===== 通知チャネルの切り替えテスト =====\n";
$notificationService = new NotificationService(new EmailNotificationFactory(), $emailConfig);
echo "初期チャネル: Email\n";

$notificationService->notify(
    'user@example.com',
    'メールでのお知らせ',
    'これはメールでの通知テストです。'
);

// SMSチャネルに切り替え
echo "チャネルを SMS に切り替え\n";
$notificationService->changeChannel(new SmsNotificationFactory(), $smsConfig);

$notificationService->notify(
    '+81901234567',
    '',
    'これはSMSでの通知テストです。'
);

// プッシュ通知チャネルに切り替え
echo "チャネルをプッシュ通知に切り替え\n";
$notificationService->changeChannel(new PushNotificationFactory(), $pushConfig);

$notificationService->notify(
    'device_token',
    'プッシュ通知テスト',
    'これはプッシュ通知のテストです。',
    [],
    ['payload' => ['test' => true]]
);

echo "\n===== テスト完了 =====\n";

# 通知システム（Abstract Factory パターン実装）

このモジュールは、Abstract Factory パターンを使用して、さまざまな通知チャネル（メール、SMS、プッシュ通知）を抽象化する実装例です。実際のバックエンド開発で必要となる通知機能を簡潔に実装しています。

## 概要

このシステムは以下の機能を提供します：

1. 異なる通知チャネル（メール、SMS、プッシュ通知）の統一的なインターフェース
2. チャネル固有の機能を損なわずに通知送信を抽象化
3. 実行時に通知チャネルを動的に切り替える機能
4. 複数の通知チャネルを組み合わせて使用する機能

## システム構成

システムは以下のコンポーネントで構成されています：

### インターフェース

- `Message`: メッセージの内容と形式を定義するインターフェース
- `Sender`: 実際の送信処理を行うインターフェース
- `NotificationFactory`: 上記のコンポーネントを生成するファクトリのインターフェース

### 具体的な実装

- **メール通知**
  - `EmailMessage`: メールメッセージの実装
  - `EmailSender`: メール送信の実装
  - `EmailNotificationFactory`: メール通知コンポーネントのファクトリ

- **SMS通知**
  - `SmsMessage`: SMSメッセージの実装
  - `SmsSender`: SMS送信の実装
  - `SmsNotificationFactory`: SMS通知コンポーネントのファクトリ

- **プッシュ通知**
  - `PushMessage`: プッシュ通知メッセージの実装
  - `PushSender`: プッシュ通知送信の実装
  - `PushNotificationFactory`: プッシュ通知コンポーネントのファクトリ

### クライアント

- `NotificationService`: 単一の通知チャネルを使用するサービス
- `CompositeNotificationService`: 複数の通知チャネルを組み合わせて使用するサービス

## 使用例

### 基本的な使用方法

```php
// Eメール通知の設定
$emailConfig = [
    'smtp_host' => 'smtp.example.com',
    'smtp_port' => 587,
    'smtp_username' => 'user@example.com',
    'smtp_password' => 'password123',
    'smtp_encryption' => 'tls'
];

// Eメール通知サービスの作成
$emailService = new NotificationService(
    new EmailNotificationFactory(), 
    $emailConfig
);

// 通知の送信
$result = $emailService->notify(
    'recipient@example.com',
    'お支払い完了のお知らせ',
    'ご注文のお支払いが完了しました。',
    [
        ['path' => '/path/to/invoice.pdf', 'type' => 'application/pdf']
    ]
);

// ステータスの確認
echo "送信結果: " . ($result ? '成功' : '失敗');
echo "送信ステータス: " . $emailService->getStatus();
```

### 通知チャネルの切り替え

```php
// 初期設定でメール通知
$service = new NotificationService(new EmailNotificationFactory(), $emailConfig);

// SMSチャネルに切り替え
$smsConfig = [
    'provider' => 'twilio',
    'api_key' => 'your_api_key',
    'api_secret' => 'your_api_secret',
    'sender_id' => 'MYCOMPANY'
];
$service->changeChannel(new SmsNotificationFactory(), $smsConfig);

// SMS形式で通知を送信
$service->notify(
    '+81901234567',
    '',
    'お支払いが完了しました。'
);
```

### 複数チャネルでの通知

```php
// 複合通知サービスの作成
$compositeService = new CompositeNotificationService();
$compositeService->addService('email', $emailService);
$compositeService->addService('sms', $smsService);
$compositeService->addService('push', $pushService);

// すべてのチャネルで通知
$allResults = $compositeService->notifyAll(
    'user@example.com', // 各チャネルに対応した受信者形式が必要
    '重要なお知らせ',
    'システムメンテナンスのお知らせ'
);

// 選択したチャネルのみで通知
$selectedResults = $compositeService->notifySelected(
    ['email', 'sms'], // メールとSMSのみで送信
    'user@example.com',
    '緊急お知らせ',
    '緊急メンテナンスのお知らせ'
);
```

## 実務での活用例

このモジュールは以下のような実務シーンで活用できます：

1. **EC サイト**: 注文確認、発送通知、支払い完了通知など
2. **認証システム**: 二要素認証コードの送信、パスワードリセット通知など
3. **予約システム**: 予約確認、リマインダー通知など
4. **イベント管理**: イベント開催通知、キャンセル通知など
5. **アラート通知**: システム障害、セキュリティアラートなど
6. **マーケティング**: キャンペーン通知、ニュースレター配信など

## Abstract Factory パターンの利点

このシステムでは、Abstract Factory パターンを採用することで以下の利点が得られます：

1. **コードの分離**: 通知チャネルの実装と、それを使用するビジネスロジックが分離されています。クライアントコードは具体的な実装を知る必要がなく、抽象インターフェースのみを使用します。

2. **拡張性**: 新しい通知チャネル（例: Slack通知、Microsoft Teams通知など）を追加する場合、既存のコードを変更せずに新しいファクトリクラスと具体的な実装クラスを追加するだけで済みます。

3. **一貫性の保証**: 各通知チャネルのファクトリは、互いに協調して動作する関連コンポーネント（メッセージと送信者）を確実に生成します。

4. **テストの容易さ**: モックファクトリを使用することで、実際の外部通知サービスに接続せずにテストを行うことができます。

## 実装上の注意点

実際のプロジェクトでこのパターンを利用する際の注意点：

1. **設定の管理**: 各通知チャネルには異なる設定が必要です。設定の管理方法（設定ファイル、環境変数、データベースなど）を適切に選択してください。

2. **エラー処理**: 通知送信は外部サービスに依存するため、様々なエラーが発生する可能性があります。適切な例外処理とリトライ機構を実装することが重要です。

3. **セキュリティ**: API キーやパスワードなどの機密情報を安全に管理してください。

4. **ロギング**: 通知の送信ステータスを適切にロギングし、問題発生時のデバッグや監査に役立てましょう。

5. **非同期処理**: 大量の通知を扱う場合は、キューシステムなどを利用して非同期処理を実装することを検討してください。

## まとめ

このAbstract Factoryパターンの実装例は、実際のバックエンド開発において重要な通知システムを抽象化する方法を示しています。このパターンを活用することで、柔軟で拡張性の高い通知システムを構築することができます。様々な通知チャネルを統一的なインターフェースで扱いつつ、各チャネル固有の機能も活用できるという利点があります。

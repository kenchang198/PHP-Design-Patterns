<?php

namespace App\Patterns\AbstractFactory\Advanced;

/**
 * メッセージインターフェース
 * 各種通知で送信されるメッセージの基本形式を定義
 */
interface Message
{
    /**
     * 受信者を設定
     *
     * @param string $recipient 受信者
     * @return void
     */
    public function setRecipient(string $recipient): void;
    
    /**
     * 件名を設定
     *
     * @param string $subject 件名
     * @return void
     */
    public function setSubject(string $subject): void;
    
    /**
     * 本文を設定
     *
     * @param string $content 本文
     * @return void
     */
    public function setContent(string $content): void;
    
    /**
     * 添付ファイルを追加
     *
     * @param string $filePath ファイルパス
     * @param string $mimeType MIMEタイプ
     * @return void
     */
    public function addAttachment(string $filePath, string $mimeType): void;
    
    /**
     * フォーマット済みのメッセージを取得
     *
     * @return string フォーマット済みメッセージ
     */
    public function getFormattedMessage(): string;
}

/**
 * 送信者インターフェース
 * 各種通知の送信処理を定義
 */
interface Sender
{
    /**
     * 構成情報を設定
     *
     * @param array $config 構成情報
     * @return void
     */
    public function configure(array $config): void;
    
    /**
     * メッセージを送信
     *
     * @param Message $message 送信するメッセージ
     * @return bool 送信成功したかどうか
     */
    public function send(Message $message): bool;
    
    /**
     * 送信ステータスを取得
     *
     * @return string 送信ステータス
     */
    public function getDeliveryStatus(): string;
    
    /**
     * 送信ログを取得
     *
     * @return array 送信ログ
     */
    public function getDeliveryLogs(): array;
}

/**
 * 通知ファクトリインターフェース
 * 各種通知システムの具体的なコンポーネントを生成
 */
interface NotificationFactory
{
    /**
     * メッセージオブジェクトを生成
     *
     * @return Message
     */
    public function createMessage(): Message;
    
    /**
     * 送信者オブジェクトを生成
     *
     * @return Sender
     */
    public function createSender(): Sender;
}

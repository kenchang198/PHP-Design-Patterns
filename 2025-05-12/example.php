<?php

require_once 'TraditionalEmail.php';
require_once 'EmailBuilder.php';

// 1. 従来のパターン
$traditionalEmail = new TraditionalEmail();
$traditionalEmail->setTo('customer@example.com');
$traditionalEmail->setToName('田中太郎');
$traditionalEmail->setFrom('noreply@myapp.com');
$traditionalEmail->setFromName('MyApp サポート');
$traditionalEmail->setSubject('重要なお知らせ');
$traditionalEmail->setTemplate('notification');
$traditionalEmail->setTemplateData([
    'username' => '田中太郎',
    'action' => 'パスワード変更'
]);
$traditionalEmail->send();

// 2. ビルダーパターン
$builderEmail = (new EmailBuilder())
    ->to('customer@example.com', '田中太郎')
    ->from('noreply@myapp.com')
    ->fromName('MyApp サポート')
    ->subject('重要なお知らせ')
    ->template('notification')
    ->withData([
        'username' => '田中太郎',
        'action' => 'パスワード変更'
    ])
    ->send();

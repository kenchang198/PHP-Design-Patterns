<?php

namespace App\Patterns\AbstractFactory\Task;

require_once 'LightThemeFactory.php';
require_once 'DarkThemeFactory.php';

/**
 * UIアプリケーション：テーマを切り替えられるアプリケーション
 */
class UIApplication
{
    private UIFactory $factory;
    
    public function __construct(UIFactory $factory)
    {
        $this->factory = $factory;
    }
    
    public function changeTheme(UIFactory $factory): void
    {
        $this->factory = $factory;
        echo "テーマを変更しました\n";
    }
    
    public function createUI(): void
    {
        // ボタンを作成
        $loginButton = $this->factory->createButton('ログイン');
        $registerButton = $this->factory->createButton('新規登録');
        
        // フォームを作成
        $loginForm = $this->factory->createForm('login', [
            'username' => 'text',
            'password' => 'password'
        ]);
        
        // アラートを作成
        $successAlert = $this->factory->createAlert('success');
        $errorAlert = $this->factory->createAlert('error');
        
        // コンポーネントの表示
        echo "=== UI コンポーネント ===\n";
        echo "ボタン: " . $loginButton->render() . "\n";
        echo "フォーム: " . $loginForm->render() . "\n";
        echo "アラート: " . $successAlert->render() . "\n";
        
        // コンポーネントのイベント
        echo "\n=== UI イベント ===\n";
        echo $loginButton->click() . "\n";
        echo $loginForm->submit(['username' => 'user123', 'password' => '********']) . "\n";
        echo $successAlert->show('ログインに成功しました！') . "\n";
        echo $successAlert->close() . "\n";
    }
}

// ユーザー設定（例：ダークモード設定がオンかオフか）
$isDarkMode = false;

// 適切なファクトリを選択
if ($isDarkMode) {
    $factory = new DarkThemeFactory();
    echo "ダークテーマでアプリケーションを開始します\n";
} else {
    $factory = new LightThemeFactory();
    echo "ライトテーマでアプリケーションを開始します\n";
}

// アプリケーションを初期化して使用
$app = new UIApplication($factory);
$app->createUI();

// ユーザーがテーマを切り替えたとする
echo "\n// ユーザーがテーマを切り替え\n";
$isDarkMode = true;

// テーマを切り替え
if ($isDarkMode) {
    $app->changeTheme(new DarkThemeFactory());
} else {
    $app->changeTheme(new LightThemeFactory());
}

// 新しいテーマでUIを再生成
$app->createUI();
